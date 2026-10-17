<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\SlugService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        protected SlugService $slugService
    ) {}

    /**
     * Display a listing of categories with blog count.
     * Also handles inline edit by passing the category being edited.
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('blogs')->orderBy('name')->get();
        $editCategory = null;

        if ($request->has('edit')) {
            $editCategory = Category::find($request->edit);
        }

        return view('admin.categories.index', compact('categories', 'editCategory'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:120|unique:categories,slug',
            'description' => 'nullable|string|max:500',
        ]);

        // Generate slug from name if not provided
        $slug = ! empty($validated['slug'])
            ? $validated['slug']
            : $this->slugService->generate($validated['name']);

        $slug = $this->slugService->makeUnique($slug, Category::class);

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    /**
     * Show a category (redirects to index with edit panel).
     */
    public function show(Category $category)
    {
        return redirect()->route('admin.categories.index', ['edit' => $category->id]);
    }

    /**
     * Show the form for editing the specified category.
     * Redirects to index with edit param for split-panel layout.
     */
    public function edit(Category $category)
    {
        return redirect()->route('admin.categories.index', ['edit' => $category->id]);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:120|unique:categories,slug,'.$category->id,
            'description' => 'nullable|string|max:500',
        ]);

        // Generate slug from name if not provided or if name changed
        if (! empty($validated['slug']) && $validated['slug'] !== $category->slug) {
            $slug = $this->slugService->makeUnique($validated['slug'], Category::class, $category->id);
        } elseif (empty($validated['slug']) && $category->name !== $validated['name']) {
            $slug = $this->slugService->generate($validated['name']);
            $slug = $this->slugService->makeUnique($slug, Category::class, $category->id);
        } else {
            $slug = $category->slug;
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified category (prevent if has blogs).
     */
    public function destroy(Category $category)
    {
        if ($category->blogs()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki artikel terkait.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
