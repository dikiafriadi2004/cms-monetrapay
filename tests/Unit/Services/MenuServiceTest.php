<?php

use App\Services\MenuService;

beforeEach(function () {
    $this->menuService = new MenuService;
});

describe('MenuService::validateStructure()', function () {
    it('returns true for an empty menu', function () {
        expect($this->menuService->validateStructure([]))->toBeTrue();
    });

    it('returns true for a flat menu with items within limit', function () {
        $items = array_map(fn ($i) => ['label' => "Item $i", 'children' => []], range(1, 10));

        expect($this->menuService->validateStructure($items))->toBeTrue();
    });

    it('returns true for a menu with exactly 3 levels of nesting', function () {
        $items = [
            [
                'label' => 'Level 1',
                'children' => [
                    [
                        'label' => 'Level 2',
                        'children' => [
                            [
                                'label' => 'Level 3',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        expect($this->menuService->validateStructure($items))->toBeTrue();
    });

    it('returns false for a menu with more than 3 levels of nesting', function () {
        $items = [
            [
                'label' => 'Level 1',
                'children' => [
                    [
                        'label' => 'Level 2',
                        'children' => [
                            [
                                'label' => 'Level 3',
                                'children' => [
                                    [
                                        'label' => 'Level 4',
                                        'children' => [],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        expect($this->menuService->validateStructure($items))->toBeFalse();
    });

    it('returns true for exactly 50 items', function () {
        $items = array_map(fn ($i) => ['label' => "Item $i", 'children' => []], range(1, 50));

        expect($this->menuService->validateStructure($items))->toBeTrue();
    });

    it('returns false for more than 50 items', function () {
        $items = array_map(fn ($i) => ['label' => "Item $i", 'children' => []], range(1, 51));

        expect($this->menuService->validateStructure($items))->toBeFalse();
    });

    it('counts nested items toward the 50 item limit', function () {
        // 25 root items, each with 1 child = 50 total
        $items = array_map(fn ($i) => [
            'label' => "Item $i",
            'children' => [
                ['label' => "Child of $i", 'children' => []],
            ],
        ], range(1, 25));

        expect($this->menuService->validateStructure($items))->toBeTrue();

        // 26 root items, each with 1 child = 52 total
        $items = array_map(fn ($i) => [
            'label' => "Item $i",
            'children' => [
                ['label' => "Child of $i", 'children' => []],
            ],
        ], range(1, 26));

        expect($this->menuService->validateStructure($items))->toBeFalse();
    });

    it('returns true for a complex valid structure', function () {
        $items = [
            [
                'label' => 'Home',
                'children' => [],
            ],
            [
                'label' => 'Products',
                'children' => [
                    [
                        'label' => 'Pulsa',
                        'children' => [
                            ['label' => 'Telkomsel', 'children' => []],
                            ['label' => 'Indosat', 'children' => []],
                        ],
                    ],
                    [
                        'label' => 'PPOB',
                        'children' => [
                            ['label' => 'PLN', 'children' => []],
                            ['label' => 'PDAM', 'children' => []],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'About',
                'children' => [],
            ],
        ];

        expect($this->menuService->validateStructure($items))->toBeTrue();
    });
});
