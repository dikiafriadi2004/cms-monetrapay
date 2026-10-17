<?php

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->menuService = new MenuService;
    $this->menu = Menu::forceCreate([
        'name' => 'Main Menu',
        'location' => 'header',
        'is_active' => true,
    ]);
});

describe('MenuService::buildTree()', function () {
    it('returns an empty array for a menu with no items', function () {
        $tree = $this->menuService->buildTree($this->menu->id);

        expect($tree)->toBeArray()->toBeEmpty();
    });

    it('returns flat items as root-level nodes', function () {
        MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => null,
            'type' => 'custom',
            'label' => 'Home',
            'url' => '/',
            'order' => 0,
        ]);
        MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => null,
            'type' => 'custom',
            'label' => 'About',
            'url' => '/about',
            'order' => 1,
        ]);

        $tree = $this->menuService->buildTree($this->menu->id);

        expect($tree)->toHaveCount(2);
        expect($tree[0]['label'])->toBe('Home');
        expect($tree[0]['children'])->toBeEmpty();
        expect($tree[1]['label'])->toBe('About');
        expect($tree[1]['children'])->toBeEmpty();
    });

    it('builds nested tree with parent-child relationships', function () {
        $parent = MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => null,
            'type' => 'custom',
            'label' => 'Products',
            'url' => '/products',
            'order' => 0,
        ]);
        MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => $parent->id,
            'type' => 'custom',
            'label' => 'Pulsa',
            'url' => '/products/pulsa',
            'order' => 0,
        ]);
        MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => $parent->id,
            'type' => 'custom',
            'label' => 'PPOB',
            'url' => '/products/ppob',
            'order' => 1,
        ]);

        $tree = $this->menuService->buildTree($this->menu->id);

        expect($tree)->toHaveCount(1);
        expect($tree[0]['label'])->toBe('Products');
        expect($tree[0]['children'])->toHaveCount(2);
        expect($tree[0]['children'][0]['label'])->toBe('Pulsa');
        expect($tree[0]['children'][1]['label'])->toBe('PPOB');
    });

    it('builds 3-level deep nested tree', function () {
        $level1 = MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => null,
            'type' => 'custom',
            'label' => 'Level 1',
            'url' => '/level1',
            'order' => 0,
        ]);
        $level2 = MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => $level1->id,
            'type' => 'custom',
            'label' => 'Level 2',
            'url' => '/level2',
            'order' => 0,
        ]);
        MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => $level2->id,
            'type' => 'custom',
            'label' => 'Level 3',
            'url' => '/level3',
            'order' => 0,
        ]);

        $tree = $this->menuService->buildTree($this->menu->id);

        expect($tree)->toHaveCount(1);
        expect($tree[0]['children'])->toHaveCount(1);
        expect($tree[0]['children'][0]['children'])->toHaveCount(1);
        expect($tree[0]['children'][0]['children'][0]['label'])->toBe('Level 3');
    });

    it('orders items by the order column', function () {
        MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => null,
            'type' => 'custom',
            'label' => 'Third',
            'url' => '/third',
            'order' => 2,
        ]);
        MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => null,
            'type' => 'custom',
            'label' => 'First',
            'url' => '/first',
            'order' => 0,
        ]);
        MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => null,
            'type' => 'custom',
            'label' => 'Second',
            'url' => '/second',
            'order' => 1,
        ]);

        $tree = $this->menuService->buildTree($this->menu->id);

        expect($tree[0]['label'])->toBe('First');
        expect($tree[1]['label'])->toBe('Second');
        expect($tree[2]['label'])->toBe('Third');
    });
});

describe('MenuService::saveOrder()', function () {
    it('saves a flat list of items', function () {
        $items = [
            ['label' => 'Home', 'type' => 'custom', 'url' => '/', 'children' => []],
            ['label' => 'About', 'type' => 'custom', 'url' => '/about', 'children' => []],
            ['label' => 'Contact', 'type' => 'custom', 'url' => '/contact', 'children' => []],
        ];

        $this->menuService->saveOrder($this->menu->id, $items);

        $savedItems = MenuItem::where('menu_id', $this->menu->id)->orderBy('order')->get();

        expect($savedItems)->toHaveCount(3);
        expect($savedItems[0]->label)->toBe('Home');
        expect($savedItems[0]->order)->toBe(0);
        expect($savedItems[1]->label)->toBe('About');
        expect($savedItems[1]->order)->toBe(1);
        expect($savedItems[2]->label)->toBe('Contact');
        expect($savedItems[2]->order)->toBe(2);
    });

    it('saves nested items with correct parent relationships', function () {
        $items = [
            [
                'label' => 'Products',
                'type' => 'custom',
                'url' => '/products',
                'children' => [
                    ['label' => 'Pulsa', 'type' => 'custom', 'url' => '/pulsa', 'children' => []],
                    ['label' => 'PPOB', 'type' => 'custom', 'url' => '/ppob', 'children' => []],
                ],
            ],
        ];

        $this->menuService->saveOrder($this->menu->id, $items);

        $parent = MenuItem::where('menu_id', $this->menu->id)->whereNull('parent_id')->first();
        $children = MenuItem::where('menu_id', $this->menu->id)->where('parent_id', $parent->id)->orderBy('order')->get();

        expect($parent->label)->toBe('Products');
        expect($children)->toHaveCount(2);
        expect($children[0]->label)->toBe('Pulsa');
        expect($children[1]->label)->toBe('PPOB');
    });

    it('deletes existing items before saving new ones', function () {
        // Create initial items
        MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => null,
            'type' => 'custom',
            'label' => 'Old Item',
            'url' => '/old',
            'order' => 0,
        ]);

        // Save new items
        $items = [
            ['label' => 'New Item', 'type' => 'custom', 'url' => '/new', 'children' => []],
        ];

        $this->menuService->saveOrder($this->menu->id, $items);

        $allItems = MenuItem::where('menu_id', $this->menu->id)->get();

        expect($allItems)->toHaveCount(1);
        expect($allItems[0]->label)->toBe('New Item');
    });

    it('preserves item type and target attributes', function () {
        $items = [
            [
                'label' => 'External Link',
                'type' => 'external',
                'url' => 'https://example.com',
                'target' => '_blank',
                'children' => [],
            ],
        ];

        $this->menuService->saveOrder($this->menu->id, $items);

        $saved = MenuItem::where('menu_id', $this->menu->id)->first();

        expect($saved->type)->toBe('external');
        expect($saved->url)->toBe('https://example.com');
        expect($saved->target)->toBe('_blank');
    });

    it('saves page_id and category_id when provided', function () {
        // Create a page to reference
        $page = Page::forceCreate([
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => 'Test content',
            'status' => 'published',
            'order' => 0,
        ]);

        $items = [
            [
                'label' => 'Page Link',
                'type' => 'page',
                'url' => null,
                'page_id' => $page->id,
                'category_id' => null,
                'children' => [],
            ],
        ];

        $this->menuService->saveOrder($this->menu->id, $items);

        $saved = MenuItem::where('menu_id', $this->menu->id)->first();

        expect($saved->type)->toBe('page');
        expect($saved->page_id)->toBe($page->id);
    });

    it('handles empty items array', function () {
        // Create initial items
        MenuItem::forceCreate([
            'menu_id' => $this->menu->id,
            'parent_id' => null,
            'type' => 'custom',
            'label' => 'Old Item',
            'url' => '/old',
            'order' => 0,
        ]);

        $this->menuService->saveOrder($this->menu->id, []);

        $allItems = MenuItem::where('menu_id', $this->menu->id)->get();

        expect($allItems)->toHaveCount(0);
    });
});
