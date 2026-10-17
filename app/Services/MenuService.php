<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MenuService
{
    /**
     * Build nested menu tree from flat menu items.
     * Returns a nested array structure suitable for rendering multi-level menus.
     * Items are ordered by their 'order' column.
     *
     * @param  int  $menuId  The menu ID to build tree for
     * @return array Nested array of menu items with 'children' key
     */
    public function buildTree(int $menuId): array
    {
        $items = MenuItem::where('menu_id', $menuId)
            ->orderBy('order')
            ->get()
            ->toArray();

        return $this->buildNestedTree($items, null);
    }

    /**
     * Recursively build nested tree from flat items array.
     *
     * @param  array  $items  Flat array of menu items
     * @param  int|null  $parentId  The parent ID to filter by
     * @return array Nested array with 'children' key
     */
    protected function buildNestedTree(array $items, ?int $parentId): array
    {
        $tree = [];

        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->buildNestedTree($items, $item['id']);
                $item['children'] = $children;
                $tree[] = $item;
            }
        }

        return $tree;
    }

    /**
     * Save menu item order from drag-and-drop data.
     * Accepts a nested array structure representing the new order.
     * Deletes existing items and re-creates them with new order/parent relationships.
     *
     * @param  int  $menuId  The menu ID
     * @param  array  $items  Array of items with structure: [{id, label, type, url, target, page_id, category_id, children: [...]}]
     */
    public function saveOrder(int $menuId, array $items): void
    {
        DB::transaction(function () use ($menuId, $items) {
            // Delete all existing items for this menu
            MenuItem::where('menu_id', $menuId)->delete();

            // Re-insert items with correct order and parent relationships
            $this->insertItems($menuId, $items, null, 0);
        });

        // Invalidate cached menu for the affected location
        $menu = Menu::find($menuId);
        if ($menu) {
            Cache::forget('menu.location.'.$menu->location);
        }
    }

    /**
     * Recursively insert menu items with correct parent_id and order.
     *
     * @param  int  $menuId  The menu ID
     * @param  array  $items  Array of items to insert
     * @param  int|null  $parentId  The parent item ID
     * @param  int  $startOrder  Starting order index
     * @return int The next available order index
     */
    protected function insertItems(int $menuId, array $items, ?int $parentId, int $startOrder): int
    {
        $order = $startOrder;

        foreach ($items as $item) {
            $menuItem = MenuItem::create([
                'menu_id' => $menuId,
                'parent_id' => $parentId,
                'type' => $item['type'] ?? 'custom',
                'label' => $item['label'],
                'url' => $item['url'] ?? null,
                'target' => $item['target'] ?? '_self',
                'page_id' => $item['page_id'] ?? null,
                'category_id' => $item['category_id'] ?? null,
                'order' => $order,
            ]);

            $order++;

            // Recursively insert children
            if (! empty($item['children'])) {
                $this->insertItems($menuId, $item['children'], $menuItem->id, 0);
            }
        }

        return $order;
    }

    /**
     * Validate menu structure constraints.
     * - Maximum 3 levels of nesting depth (root = level 1, child = level 2, grandchild = level 3)
     * - Maximum 50 items total per menu
     *
     * @param  array  $items  Nested array of menu items
     * @return bool True if valid, false if constraints violated
     */
    public function validateStructure(array $items): bool
    {
        $totalCount = $this->countItems($items);

        if ($totalCount > 50) {
            return false;
        }

        $maxDepth = $this->getMaxDepth($items);

        if ($maxDepth > 3) {
            return false;
        }

        return true;
    }

    /**
     * Count total number of items in a nested structure.
     *
     * @param  array  $items  Nested array of menu items
     * @return int Total item count
     */
    protected function countItems(array $items): int
    {
        $count = 0;

        foreach ($items as $item) {
            $count++;

            if (! empty($item['children'])) {
                $count += $this->countItems($item['children']);
            }
        }

        return $count;
    }

    /**
     * Get the maximum depth of a nested structure.
     * Root level = 1, child = 2, grandchild = 3.
     *
     * @param  array  $items  Nested array of menu items
     * @param  int  $currentDepth  Current depth level
     * @return int Maximum depth found
     */
    protected function getMaxDepth(array $items, int $currentDepth = 1): int
    {
        if (empty($items)) {
            return 0;
        }

        $maxDepth = $currentDepth;

        foreach ($items as $item) {
            if (! empty($item['children'])) {
                $childDepth = $this->getMaxDepth($item['children'], $currentDepth + 1);
                $maxDepth = max($maxDepth, $childDepth);
            }
        }

        return $maxDepth;
    }
}
