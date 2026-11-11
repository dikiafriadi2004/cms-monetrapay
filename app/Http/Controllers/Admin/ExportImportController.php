<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Page;
use App\Traits\RespondsWithError;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExportImportController extends Controller
{
    use RespondsWithError;

    /**
     * Show export/import tools
     */
    public function index(): View
    {
        $this->authorize('setting.edit');

        $stats = [
            'blogs' => Blog::count(),
            'pages' => Page::count(),
            'categories' => Category::count(),
        ];

        $exports = collect(Storage::disk('local')->files('exports'))
            ->filter(fn($file) => str_ends_with($file, '.json'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('local')->size($file),
                    'date' => Storage::disk('local')->lastModified($file),
                ];
            })
            ->sortByDesc('date')
            ->values();

        return view('admin.tools.export-import', compact('stats', 'exports'));
    }

    /**
     * Export blogs to JSON
     */
    public function exportBlogs(): JsonResponse|RedirectResponse
    {
        $this->authorize('setting.edit');

        try {
            $blogs = Blog::with(['author:id,name,email', 'categories:id,name,slug'])
                ->get()
                ->map(function ($blog) {
                    return [
                        'title' => $blog->title,
                        'slug' => $blog->slug,
                        'excerpt' => $blog->excerpt,
                        'content' => $blog->content,
                        'featured_image' => $blog->featured_image,
                        'status' => $blog->status,
                        'published_at' => $blog->published_at?->toISOString(),
                        'meta_title' => $blog->meta_title,
                        'meta_description' => $blog->meta_description,
                        'meta_keywords' => $blog->meta_keywords,
                        'author_email' => $blog->author?->email,
                        'categories' => $blog->categories->pluck('slug')->toArray(),
                        'created_at' => $blog->created_at->toISOString(),
                    ];
                });

            $filename = 'exports/blogs_' . now()->format('Y-m-d_His') . '.json';
            Storage::disk('local')->put($filename, json_encode($blogs, JSON_PRETTY_PRINT));

            return $this->successResponse(
                'Data blog berhasil diekspor. File: ' . basename($filename)
            );
        } catch (\Throwable $e) {
            return $this->handleError($e, 'Gagal mengekspor data blog.');
        }
    }

    /**
     * Export pages to JSON
     */
    public function exportPages(): JsonResponse|RedirectResponse
    {
        $this->authorize('setting.edit');

        try {
            $pages = Page::with(['author:id,name,email'])
                ->get()
                ->map(function ($page) {
                    return [
                        'title' => $page->title,
                        'slug' => $page->slug,
                        'content' => $page->content,
                        'template' => $page->template,
                        'featured_image' => $page->featured_image,
                        'status' => $page->status,
                        'meta_title' => $page->meta_title,
                        'meta_description' => $page->meta_description,
                        'meta_keywords' => $page->meta_keywords,
                        'author_email' => $page->author?->email,
                        'created_at' => $page->created_at->toISOString(),
                    ];
                });

            $filename = 'exports/pages_' . now()->format('Y-m-d_His') . '.json';
            Storage::disk('local')->put($filename, json_encode($pages, JSON_PRETTY_PRINT));

            return $this->successResponse(
                'Data halaman berhasil diekspor. File: ' . basename($filename)
            );
        } catch (\Throwable $e) {
            return $this->handleError($e, 'Gagal mengekspor data halaman.');
        }
    }

    /**
     * Import blogs from JSON
     */
    public function importBlogs(Request $request): JsonResponse|RedirectResponse
    {
        $this->authorize('setting.edit');

        $request->validate([
            'file' => 'required|file|mimes:json|max:10240',
            'mode' => 'required|in:skip,overwrite',
        ]);

        try {
            $content = file_get_contents($request->file('file')->path());
            $blogs = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->validationError(
                    ['file' => ['File JSON tidak valid.']],
                    'File JSON tidak valid.'
                );
            }

            $imported = 0;
            $skipped = 0;
            $updated = 0;

            DB::beginTransaction();

            foreach ($blogs as $data) {
                // Find or create author
                $author = \App\Models\User::where('email', $data['author_email'] ?? null)->first()
                    ?? auth()->user();

                // Check if slug exists
                $existing = Blog::where('slug', $data['slug'])->first();

                if ($existing && $request->input('mode') === 'skip') {
                    $skipped++;
                    continue;
                }

                // Prepare blog data
                $blogData = [
                    'title' => $data['title'],
                    'slug' => $data['slug'],
                    'excerpt' => $data['excerpt'] ?? null,
                    'content' => $data['content'],
                    'featured_image' => $data['featured_image'] ?? null,
                    'status' => $data['status'] ?? 'draft',
                    'published_at' => isset($data['published_at']) ? \Carbon\Carbon::parse($data['published_at']) : null,
                    'meta_title' => $data['meta_title'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                    'meta_keywords' => $data['meta_keywords'] ?? null,
                    'user_id' => $author->id,
                ];

                if ($existing && $request->input('mode') === 'overwrite') {
                    $existing->update($blogData);
                    $blog = $existing;
                    $updated++;
                } else {
                    $blog = Blog::create($blogData);
                    $imported++;
                }

                // Sync categories
                if (!empty($data['categories'])) {
                    $categoryIds = Category::whereIn('slug', $data['categories'])->pluck('id');
                    $blog->categories()->sync($categoryIds);
                }
            }

            DB::commit();

            $message = "Impor selesai. Diimpor: {$imported}, Diperbarui: {$updated}, Dilewati: {$skipped}.";
            
            Log::info('Blog import completed', [
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
                'user' => auth()->id(),
            ]);

            return $this->successResponse($message);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handleError($e, 'Gagal mengimpor data blog.');
        }
    }

    /**
     * Import pages from JSON
     */
    public function importPages(Request $request): JsonResponse|RedirectResponse
    {
        $this->authorize('setting.edit');

        $request->validate([
            'file' => 'required|file|mimes:json|max:10240',
            'mode' => 'required|in:skip,overwrite',
        ]);

        try {
            $content = file_get_contents($request->file('file')->path());
            $pages = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->validationError(
                    ['file' => ['File JSON tidak valid.']],
                    'File JSON tidak valid.'
                );
            }

            $imported = 0;
            $skipped = 0;
            $updated = 0;

            DB::beginTransaction();

            foreach ($pages as $data) {
                // Find or create author
                $author = \App\Models\User::where('email', $data['author_email'] ?? null)->first()
                    ?? auth()->user();

                // Check if slug exists
                $existing = Page::where('slug', $data['slug'])->first();

                if ($existing && $request->input('mode') === 'skip') {
                    $skipped++;
                    continue;
                }

                // Prepare page data
                $pageData = [
                    'title' => $data['title'],
                    'slug' => $data['slug'],
                    'content' => $data['content'],
                    'template' => $data['template'] ?? 'default',
                    'featured_image' => $data['featured_image'] ?? null,
                    'status' => $data['status'] ?? 'draft',
                    'meta_title' => $data['meta_title'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                    'meta_keywords' => $data['meta_keywords'] ?? null,
                    'user_id' => $author->id,
                ];

                if ($existing && $request->input('mode') === 'overwrite') {
                    $existing->update($pageData);
                    $updated++;
                } else {
                    Page::create($pageData);
                    $imported++;
                }
            }

            DB::commit();

            $message = "Impor selesai. Diimpor: {$imported}, Diperbarui: {$updated}, Dilewati: {$skipped}.";
            
            Log::info('Page import completed', [
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
                'user' => auth()->id(),
            ]);

            return $this->successResponse($message);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handleError($e, 'Gagal mengimpor data halaman.');
        }
    }

    /**
     * Download export file
     */
    public function download(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse|RedirectResponse
    {
        $this->authorize('setting.edit');

        try {
            $filename = $request->input('file');
            $path = 'exports/' . basename($filename);

            if (!Storage::disk('local')->exists($path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            return Storage::disk('local')->download($path);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengunduh file.');
        }
    }

    /**
     * Delete export file
     */
    public function deleteExport(Request $request): JsonResponse|RedirectResponse
    {
        $this->authorize('setting.edit');

        try {
            $filename = $request->input('file') ?? $request->query('file');
            $path = 'exports/' . basename($filename);

            if (Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            return $this->successResponse('File berhasil dihapus.');
        } catch (\Throwable $e) {
            return $this->handleError($e, 'Gagal menghapus file.');
        }
    }
}
