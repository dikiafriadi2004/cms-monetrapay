<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    /**
     * Get menu by location
     */
    public function getMenuByLocation(string $location): ?array
    {
        return Cache::remember("menu_{$location}", 3600, function () use ($location) {
            $menu = Menu::where('location', $location)
                ->where('is_active', true)
                ->first();

            if (!$menu) {
                return null;
            }

            return $this->buildTree($menu->id);
        });
    }

    /**
     * Build menu tree for a specific menu ID.
     * Single query loads all items + page/category relations, then builds
     * the nested tree in PHP — eliminates N+1 queries.
     */
    public function buildTree(int $menuId): array
    {
        $allItems = MenuItem::where('menu_id', $menuId)
            ->with(['page:id,slug,title', 'category:id,slug,name'])
            ->orderBy('order')
            ->get();

        return $this->buildTreeFromCollection($allItems);
    }

    /**
     * Build menu tree from an already-loaded flat collection.
     * Groups by parent_id, resolves URLs from eager-loaded relations.
     *
     * @param  \Illuminate\Support\Collection<int,MenuItem>  $items  All items for this menu
     * @param  int|null  $parentId
     */
    protected function buildTreeFromCollection($items, ?int $parentId = null): array
    {
        $tree = [];

        foreach ($items->where('parent_id', $parentId) as $item) {
            $node = [
                'id'        => $item->id,
                'label'     => $item->label,
                'url'       => $this->resolveUrl($item),
                'target'    => $item->target ?? '_self',
                'css_class' => $item->css_class,
                'icon'      => $item->icon,
                'children'  => $this->buildTreeFromCollection($items, $item->id),
            ];

            $tree[] = $node;
        }

        return $tree;
    }

    /**
     * Build menu tree with nested children (legacy — kept for getMenuByLocation).
     *
     * @deprecated Use buildTreeFromCollection internally.
     */
    protected function buildMenuTree($items, $menuId = null): array
    {
        // $items here is a root-only subset; do a full single-query build instead
        if ($menuId !== null) {
            $allItems = MenuItem::where('menu_id', $menuId)
                ->with(['page:id,slug,title', 'category:id,slug,name'])
                ->orderBy('order')
                ->get();

            return $this->buildTreeFromCollection($allItems);
        }

        // Fallback: items is a small flat collection without parent info — legacy path
        $tree = [];
        foreach ($items as $item) {
            $tree[] = [
                'id'        => $item->id,
                'label'     => $item->label,
                'url'       => $this->resolveUrl($item),
                'target'    => $item->target ?? '_self',
                'css_class' => $item->css_class,
                'icon'      => $item->icon,
                'children'  => [],
            ];
        }

        return $tree;
    }

    /**
     * Resolve menu item URL based on type
     */
    protected function resolveUrl(MenuItem $item): string
    {
        return match ($item->type) {
            'page'     => $item->page ? route('page.show', $item->page->slug) : ($item->url ? url($item->url) : '#'),
            'category' => $item->category ? route('category.show', $item->category->slug) : ($item->url ? url($item->url) : '#'),
            'external' => $item->url ?? '#',
            default    => !empty($item->url)
                            ? (str_starts_with($item->url, 'http') ? $item->url : url($item->url))
                            : '#',
        };
    }

    /**
     * Clear menu cache
     */
    public function clearCache(string $location = null): void
    {
        if ($location) {
            Cache::forget("menu_{$location}");
        } else {
            // Clear all menu caches
            $locations = ['header', 'footer', 'sidebar'];
            foreach ($locations as $loc) {
                Cache::forget("menu_{$loc}");
            }
        }
    }

    /**
     * Check if current URL matches menu item
     */
    public function isActive(string $url): bool
    {
        $currentUrl = url()->current();
        $menuUrl = url($url);
        
        return $currentUrl === $menuUrl || str_starts_with($currentUrl, rtrim($menuUrl, '/') . '/');
    }

    /**
     * Validate menu structure (max 50 items, max depth 5)
     */
    public function validateStructure(array $items, int $depth = 0): bool
    {
        // Max 50 total items
        if ($this->countItems($items) > 50) {
            return false;
        }

        // Max depth 5
        if ($depth > 5) {
            return false;
        }

        foreach ($items as $item) {
            if (isset($item['children']) && !empty($item['children'])) {
                if (!$this->validateStructure($item['children'], $depth + 1)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Count total items in menu tree
     */
    protected function countItems(array $items): int
    {
        $count = count($items);

        foreach ($items as $item) {
            if (isset($item['children']) && !empty($item['children'])) {
                $count += $this->countItems($item['children']);
            }
        }

        return $count;
    }
}
