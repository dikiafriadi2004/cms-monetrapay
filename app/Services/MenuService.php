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

            $items = MenuItem::where('menu_id', $menu->id)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();

            return $this->buildMenuTree($items);
        });
    }

    /**
     * Build menu tree for a specific menu ID
     */
    public function buildTree(int $menuId): array
    {
        $items = MenuItem::where('menu_id', $menuId)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return $this->buildMenuTree($items, $menuId);
    }

    /**
     * Build menu tree with nested children
     */
    protected function buildMenuTree($items, $menuId = null): array
    {
        $tree = [];

        foreach ($items as $item) {
            $node = [
                'id' => $item->id,
                'label' => $item->label,
                'url' => $this->resolveUrl($item),
                'target' => $item->target ?? '_self',
                'css_class' => $item->css_class,
                'icon' => $item->icon,
                'children' => [],
            ];

            // Get children
            $children = MenuItem::where('menu_id', $menuId ?? $item->menu_id)
                ->where('parent_id', $item->id)
                ->orderBy('order')
                ->get();

            if ($children->isNotEmpty()) {
                $node['children'] = $this->buildMenuTree($children, $menuId ?? $item->menu_id);
            }

            $tree[] = $node;
        }

        return $tree;
    }

    /**
     * Resolve menu item URL based on type
     */
    protected function resolveUrl(MenuItem $item): string
    {
        return match ($item->type) {
            'page' => $item->page ? route('page.show', $item->page->slug) : '#',
            'blog-category' => $item->category ? route('category.show', $item->category->slug) : '#',
            'custom-url' => $item->url ?? '#',
            default => '#',
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
}
