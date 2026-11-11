<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Page;
use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(
        protected MenuService $menuService
    ) {}

    /**
     * Display a listing of all menus with location and item count.
     */
    public function index()
    {
        $menus = Menu::withCount('items')->orderBy('location')->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created menu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'in:header,footer,sidebar'],
        ], [
            'name.required' => 'Nama menu wajib diisi.',
            'name.max' => 'Nama menu maksimal 100 karakter.',
            'location.required' => 'Lokasi menu wajib dipilih.',
            'location.in' => 'Lokasi menu harus salah satu dari: header, footer, sidebar.',
        ]);

        // Check max 10 active menus
        $activeMenuCount = Menu::where('is_active', true)->count();
        if ($activeMenuCount >= 10) {
            return back()->withInput()->with('error', 'Maksimal 10 menu aktif. Nonaktifkan menu lain terlebih dahulu.');
        }

        Menu::create([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dibuat.');
    }

    /**
     * Show the menu builder interface with drag-and-drop.
     */
    public function edit(Menu $menu)
    {
        $menuTree = $this->menuService->buildTree($menu->id);
        $pages = Page::where('status', 'published')->orderBy('title')->get(['id', 'title', 'slug']);
        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);

        return view('admin.menus.edit', compact('menu', 'menuTree', 'pages', 'categories'));
    }

    /**
     * Update menu name/location.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'in:header,footer,sidebar'],
        ], [
            'name.required' => 'Nama menu wajib diisi.',
            'name.max' => 'Nama menu maksimal 100 karakter.',
            'location.required' => 'Lokasi menu wajib dipilih.',
            'location.in' => 'Lokasi menu harus salah satu dari: header, footer, sidebar.',
        ]);

        $menu->update([
            'name' => $validated['name'],
            'location' => $validated['location'],
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Delete menu and all its items.
     */
    public function destroy(Menu $menu)
    {
        $menu->items()->delete();
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Save menu items order from drag-and-drop JSON data.
     * Validates structure (max 3 levels, max 50 items).
     */
    public function saveItems(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'max:50'],
        ], [
            'items.required' => 'Data item menu wajib diisi.',
            'items.max' => 'Maksimal 50 item per menu.',
        ]);

        $items = $validated['items'];

        // Validate structure constraints
        if (! $this->menuService->validateStructure($items)) {
            return response()->json([
                'success' => false,
                'message' => 'Struktur menu tidak valid. Maksimal 3 level kedalaman dan 50 item.',
            ], 422);
        }

        // Save the menu items order
        $this->menuService->saveOrder($menu->id, $items);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil disimpan.',
        ]);
    }
}
