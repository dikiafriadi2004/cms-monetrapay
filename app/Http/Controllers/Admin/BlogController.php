<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use App\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BlogController extends Controller
{
    public function __construct(
        protected SlugService $slugService
    ) {}

    /**
     * Export blogs to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = Blog::with(['author', 'categories', 'tags'])->orderByDesc('created_at');

        if ($request->filled('status') && $request->status !== 'trashed') {
            $query->where('status', $request->status);
        }

        $filename = 'blogs-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Judul', 'Slug', 'Status', 'Penulis', 'Kategori', 'Tags', 'Dipublikasi', 'Dibuat']);

            $query->chunk(500, function ($blogs) use ($out) {
                foreach ($blogs as $blog) {
                    fputcsv($out, [
                        $blog->id,
                        $blog->title,
                        $blog->slug,
                        $blog->status,
                        $blog->author?->name ?? '—',
                        $blog->categories->pluck('name')->join(', '),
                        $blog->tags->pluck('name')->join(', '),
                        $blog->published_at?->toDateTimeString() ?? '—',
                        $blog->created_at->toDateTimeString(),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Display a listing of blogs with search and filters.
     */
    public function index(Request $request)
    {
        $query = Blog::with(['author', 'categories']);

        // Filter by status (including trashed)
        if ($request->filled('status')) {
            if ($request->status === 'trashed') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        $blogs = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created blog.
     */
    public function store(BlogRequest $request)
    {
        $validated = $request->validated();

        // Generate slug from title using SlugService
        $slug = $this->slugService->generate($validated['title']);
        $slug = $this->slugService->makeUnique($slug, Blog::class);

        // Handle featured image upload
        $featuredImage = null;
        if ($request->hasFile('featured_image')) {
            $featuredImage = $request->file('featured_image')->store('blogs/featured', 'public');
        }

        // Determine scheduled_at (only relevant for draft status)
        $scheduledAt = null;
        if ($validated['status'] === 'draft' && ! empty($validated['scheduled_at'])) {
            $scheduledAt = $validated['scheduled_at'];
        }

        // Create the blog post
        $blog = Blog::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => Purifier::clean($validated['content'], 'blog'),
            'excerpt' => $validated['excerpt'] ?? null,
            'featured_image' => $featuredImage,
            'status' => $validated['status'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'published_at' => $validated['status'] === 'published' ? now() : null,
            'scheduled_at' => $scheduledAt,
        ]);

        // Sync categories
        $blog->categories()->sync($validated['categories']);

        // Handle tags (create on-the-fly if they don't exist)
        if (! empty($validated['tags'])) {
            $tagIds = $this->resolveTagIds($validated['tags']);
            $blog->tags()->sync($tagIds);
        }

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Artikel berhasil dibuat.');
    }

    /**
     * Show a single blog (redirects to edit).
     */
    public function show(Blog $blog)
    {
        return redirect()->route('admin.blogs.edit', $blog);
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit(Blog $blog)
    {
        $blog->load(['categories', 'tags']);
        $categories = Category::orderBy('name')->get();

        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified blog.
     */
    public function update(BlogRequest $request, Blog $blog)
    {
        $validated = $request->validated();

        // Re-generate slug if title changed
        if ($blog->title !== $validated['title']) {
            $slug = $this->slugService->generate($validated['title']);
            $slug = $this->slugService->makeUnique($slug, Blog::class, $blog->id);
        } else {
            $slug = $blog->slug;
        }

        // If a custom slug was provided, use it
        if (! empty($validated['slug']) && $validated['slug'] !== $blog->slug) {
            $slug = $validated['slug'];
        }

        // Handle featured image upload/removal
        $featuredImage = $blog->featured_image;
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $featuredImage = $request->file('featured_image')->store('blogs/featured', 'public');
        } elseif ($request->boolean('remove_featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $featuredImage = null;
        }

        // Determine published_at
        $publishedAt = $blog->published_at;
        if ($validated['status'] === 'published' && ! $blog->published_at) {
            $publishedAt = now();
        } elseif ($validated['status'] === 'draft') {
            $publishedAt = null;
        }

        // Determine scheduled_at (only for draft status)
        $scheduledAt = null;
        if ($validated['status'] === 'draft' && ! empty($validated['scheduled_at'])) {
            $scheduledAt = $validated['scheduled_at'];
        }

        // Update the blog post
        $blog->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => Purifier::clean($validated['content'], 'blog'),
            'excerpt' => $validated['excerpt'] ?? null,
            'featured_image' => $featuredImage,
            'status' => $validated['status'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'published_at' => $publishedAt,
            'scheduled_at' => $scheduledAt,
        ]);

        // Sync categories
        $blog->categories()->sync($validated['categories']);

        // Handle tags
        if (isset($validated['tags'])) {
            $tagIds = $this->resolveTagIds($validated['tags']);
            $blog->tags()->sync($tagIds);
        } else {
            $blog->tags()->detach();
        }

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Soft delete the specified blog (move to trash).
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Artikel berhasil dipindahkan ke trash.');
    }

    /**
     * Restore a soft-deleted blog from trash.
     */
    public function restore(int $id)
    {
        $blog = Blog::onlyTrashed()->findOrFail($id);
        $blog->restore();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Artikel berhasil dipulihkan dari trash.');
    }

    /**
     * Permanently delete a blog from trash.
     */
    public function forceDelete(int $id)
    {
        $blog = Blog::onlyTrashed()->findOrFail($id);

        // Delete featured image if exists
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        // Detach relationships
        $blog->categories()->detach();
        $blog->tags()->detach();

        $blog->forceDelete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Artikel berhasil dihapus permanen.');
    }

    /**
     * Bulk action: publish, draft, trash, or restore selected blogs.
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:publish,draft,trash,restore,force_delete'],
            'ids' => ['required', 'array', 'min:1', 'max:100'],
            'ids.*' => ['integer'],
        ]);

        $action = $validated['action'];
        $ids = $validated['ids'];
        $count = 0;
        $msg = 'Tidak ada artikel yang diproses.';

        switch ($action) {
            case 'publish':
                $count = Blog::whereIn('id', $ids)->whereNull('deleted_at')
                    ->update(['status' => 'published', 'published_at' => now()]);
                $msg = "{$count} artikel berhasil dipublikasi.";
                break;
            case 'draft':
                $count = Blog::whereIn('id', $ids)->whereNull('deleted_at')
                    ->update(['status' => 'draft']);
                $msg = "{$count} artikel diubah ke draft.";
                break;
            case 'trash':
                $count = Blog::whereIn('id', $ids)->whereNull('deleted_at')->get()->each->delete()->count();
                $msg = "{$count} artikel dipindahkan ke trash.";
                break;
            case 'restore':
                $count = Blog::onlyTrashed()->whereIn('id', $ids)->get()->each->restore()->count();
                $msg = "{$count} artikel dipulihkan.";
                break;
            case 'force_delete':
                $blogs = Blog::onlyTrashed()->whereIn('id', $ids)->get();
                foreach ($blogs as $b) {
                    if ($b->featured_image) {
                        Storage::disk('public')->delete($b->featured_image);
                    }
                    $b->categories()->detach();
                    $b->tags()->detach();
                    $b->forceDelete();
                    $count++;
                }
                $msg = "{$count} artikel dihapus permanen.";
                break;
        }

        return redirect()->route('admin.blogs.index', $request->only(['status', 'category_id', 'search']))
            ->with('success', $msg);
    }

    /**
     * Autosave draft content (called every 30s from editor).
     * Updates only the existing record's draft fields.
     */
    public function autosave(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:200'],
            'content' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
        ]);

        $blog->update(array_filter($validated, fn ($v) => $v !== null));

        return response()->json([
            'success' => true,
            'saved_at' => now()->toIso8601String(),
            'message' => 'Tersimpan otomatis '.now()->translatedFormat('H:i:s'),
        ]);
    }

    /**
     * Resolve tag names to tag IDs, creating new tags on-the-fly.
     */
    protected function resolveTagIds(array $tagNames): array
    {
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if (empty($tagName)) {
                continue;
            }

            $slug = $this->slugService->generate($tagName);

            $tag = Tag::firstOrCreate(
                ['slug' => $slug],
                ['name' => $tagName, 'slug' => $slug]
            );

            $tagIds[] = $tag->id;
        }

        return $tagIds;
    }
}
