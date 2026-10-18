<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

class PageController extends Controller
{
    public function __construct(
        protected SlugService $slugService
    ) {}

    /**
     * Display a listing of pages showing hierarchy.
     */
    public function index(Request $request)
    {
        $query = Page::with(['parent', 'children']);

        // Filter by status (including trashed)
        if ($request->filled('status')) {
            if ($request->status === 'trashed') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->status);
            }
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        // Get all pages for hierarchy display (non-trashed view)
        if (! $request->filled('status') || $request->status !== 'trashed') {
            $pages = $query->orderBy('order')->orderBy('title')->paginate(20)->withQueryString();
        } else {
            $pages = $query->orderByDesc('deleted_at')->paginate(20)->withQueryString();
        }

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        // Only show pages at depth < 3 as potential parents
        $parentPages = $this->getAvailableParentPages();

        $templates = $this->getAvailableTemplates();

        return view('admin.pages.create', compact('parentPages', 'templates'));
    }

    /**
     * Store a newly created page.
     */
    public function store(PageRequest $request)
    {
        $validated = $request->validated();

        // Generate slug from title using SlugService
        if (! empty($validated['slug'])) {
            $slug = $validated['slug'];
        } else {
            $slug = $this->slugService->generate($validated['title']);
            $slug = $this->slugService->makeUnique($slug, Page::class);
        }

        // Handle featured image upload
        $featuredImage = null;
        if ($request->hasFile('featured_image')) {
            $featuredImage = $request->file('featured_image')->store('pages/featured', 'public');
        }

        // Create the page
        Page::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => isset($validated['content']) ? Purifier::clean($validated['content'], 'blog') : null,
            'parent_id' => $validated['parent_id'] ?? null,
            'template' => $validated['template'] ?? 'default',
            'status' => $validated['status'],
            'featured_image' => $featuredImage,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'use_page_builder' => $request->has('use_page_builder') ? true : false,
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dibuat.');
    }

    /**
     * Show a page (redirects to edit).
     */
    public function show(Page $page)
    {
        return redirect()->route('admin.pages.edit', $page);
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        $page->load(['parent', 'children']);

        // Get available parent pages (exclude current page and its descendants)
        $parentPages = $this->getAvailableParentPages($page->id);

        $templates = $this->getAvailableTemplates();

        return view('admin.pages.edit', compact('page', 'parentPages', 'templates'));
    }

    /**
     * Update the specified page.
     */
    public function update(PageRequest $request, Page $page)
    {
        $validated = $request->validated();

        // Re-generate slug if title changed and no custom slug provided
        if (! empty($validated['slug']) && $validated['slug'] !== $page->slug) {
            $slug = $validated['slug'];
        } elseif ($page->title !== $validated['title']) {
            $slug = $this->slugService->generate($validated['title']);
            $slug = $this->slugService->makeUnique($slug, Page::class, $page->id);
        } else {
            $slug = $page->slug;
        }

        // Handle featured image upload/removal
        $featuredImage = $page->featured_image;
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $featuredImage = $request->file('featured_image')->store('pages/featured', 'public');
        } elseif ($request->boolean('remove_featured_image')) {
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $featuredImage = null;
        }

        // Update the page
        $page->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => isset($validated['content']) ? Purifier::clean($validated['content'], 'blog') : null,
            'parent_id' => $validated['parent_id'] ?? null,
            'template' => $validated['template'] ?? 'default',
            'status' => $validated['status'],
            'featured_image' => $featuredImage,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'use_page_builder' => $request->has('use_page_builder') ? true : false,
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil diperbarui.');
    }

    /**
     * Soft delete the specified page (move to trash).
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dipindahkan ke trash.');
    }

    /**
     * Restore a soft-deleted page from trash.
     */
    public function restore(int $id)
    {
        $page = Page::onlyTrashed()->findOrFail($id);
        $page->restore();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dipulihkan dari trash.');
    }

    /**
     * Permanently delete a page from trash.
     */
    public function forceDelete(int $id)
    {
        $page = Page::onlyTrashed()->findOrFail($id);

        // Delete featured image if exists
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->forceDelete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dihapus permanen.');
    }

    /**
     * Bulk action for pages.
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
        $msg = 'Tidak ada halaman yang diproses.';

        switch ($action) {
            case 'publish':
                $count = Page::whereIn('id', $ids)->whereNull('deleted_at')
                    ->update(['status' => 'published']);
                $msg = "{$count} halaman dipublikasi.";
                break;
            case 'draft':
                $count = Page::whereIn('id', $ids)->whereNull('deleted_at')
                    ->update(['status' => 'draft']);
                $msg = "{$count} halaman diubah ke draft.";
                break;
            case 'trash':
                $count = Page::whereIn('id', $ids)->whereNull('deleted_at')->get()->each->delete()->count();
                $msg = "{$count} halaman dipindahkan ke trash.";
                break;
            case 'restore':
                $count = Page::onlyTrashed()->whereIn('id', $ids)->get()->each->restore()->count();
                $msg = "{$count} halaman dipulihkan.";
                break;
            case 'force_delete':
                $pages = Page::onlyTrashed()->whereIn('id', $ids)->get();
                foreach ($pages as $p) {
                    if ($p->featured_image) {
                        Storage::disk('public')->delete($p->featured_image);
                    }
                    $p->forceDelete();
                    $count++;
                }
                $msg = "{$count} halaman dihapus permanen.";
                break;
        }

        return redirect()->route('admin.pages.index')->with('success', $msg);
    }

    /**
     * Get pages that can be used as parents.
     * Only pages at depth < 3 are eligible (so children would be at max depth 3).
     * Optionally exclude a page and its descendants (for edit form).
     */
    protected function getAvailableParentPages(?int $excludeId = null): Collection
    {
        $query = Page::whereNull('deleted_at')->orderBy('title');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $allPages = $query->get();

        // Filter to only pages at depth 1 or 2 (so their children would be at depth 2 or 3)
        $availableParents = $allPages->filter(function (Page $page) use ($excludeId) {
            $depth = $this->getPageDepth($page);

            // Only allow as parent if depth < 3
            if ($depth >= 3) {
                return false;
            }

            // If editing, exclude descendants of the current page
            if ($excludeId && $this->isDescendantOf($page, $excludeId)) {
                return false;
            }

            return true;
        });

        return $availableParents;
    }

    /**
     * Calculate the depth of a page in the hierarchy.
     */
    protected function getPageDepth(Page $page): int
    {
        $depth = 1;
        $current = $page;

        while ($current->parent_id) {
            $depth++;
            $current = $current->parent;

            if ($depth > 10) {
                break;
            }
        }

        return $depth;
    }

    /**
     * Check if a page is a descendant of another page.
     */
    protected function isDescendantOf(Page $page, int $ancestorId): bool
    {
        $current = $page;

        while ($current->parent_id) {
            if ($current->parent_id === $ancestorId) {
                return true;
            }
            $current = $current->parent;

            if (! $current) {
                break;
            }
        }

        return false;
    }

    /**
     * Get available page templates.
     */
    protected function getAvailableTemplates(): array
    {
        return [
            'default' => 'Default',
            'full-width' => 'Full Width',
            'sidebar-left' => 'Sidebar Kiri',
            'sidebar-right' => 'Sidebar Kanan',
        ];
    }
}
