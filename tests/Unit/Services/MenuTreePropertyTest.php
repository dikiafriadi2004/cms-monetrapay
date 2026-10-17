<?php

use App\Services\MenuService;

beforeEach(function () {
    $this->service = new MenuService;
});

/**
 * Property-based tests for menu structure validation.
 */
describe('MenuService::validateStructure invariants', function () {

    /**
     * Property: empty menu is always valid
     */
    it('accepts empty menu', function () {
        expect($this->service->validateStructure([]))->toBeTrue();
    });

    /**
     * Property: depth > 3 is always invalid
     */
    it('rejects depth greater than 3', function (int $extraDepth) {
        // Build a menu chain of given depth
        $depth = 4 + $extraDepth;
        $current = ['label' => 'Item', 'children' => []];

        for ($i = 0; $i < $depth - 1; $i++) {
            $current = ['label' => "Item L{$i}", 'children' => [$current]];
        }

        expect($this->service->validateStructure([$current]))->toBeFalse();
    })->with([
        [0], [1], [5], [10], [50],
    ]);

    /**
     * Property: depth <= 3 is structurally valid (assuming under 50 items)
     */
    it('accepts depth at most 3', function () {
        $tree = [
            [
                'label' => 'L1',
                'children' => [
                    [
                        'label' => 'L2',
                        'children' => [
                            ['label' => 'L3', 'children' => []],
                        ],
                    ],
                ],
            ],
        ];

        expect($this->service->validateStructure($tree))->toBeTrue();
    });

    /**
     * Property: total > 50 items is invalid
     */
    it('rejects more than 50 items total', function (int $extra) {
        $items = [];
        for ($i = 0; $i < 50 + $extra + 1; $i++) {
            $items[] = ['label' => "Item {$i}", 'children' => []];
        }

        expect($this->service->validateStructure($items))->toBeFalse();
    })->with([
        [0], [1], [10], [50], [100],
    ]);

    /**
     * Property: exactly 50 items is valid
     */
    it('accepts exactly 50 items', function () {
        $items = [];
        for ($i = 0; $i < 50; $i++) {
            $items[] = ['label' => "Item {$i}", 'children' => []];
        }

        expect($this->service->validateStructure($items))->toBeTrue();
    });

    /**
     * Property: valid trees of varying random shapes are accepted
     */
    it('accepts random valid trees within constraints', function () {
        // Generate random tree with depth 1-3, items 1-50
        $tree = generateRandomTree(maxDepth: 3, maxItems: 30);

        expect($this->service->validateStructure($tree))->toBeTrue();
    })->repeat(20);
});

/**
 * Generate a random nested tree respecting maxDepth and maxItems constraints.
 * Total item count is capped per recursion to stay safely under maxItems.
 */
function generateRandomTree(int $maxDepth, int $maxItems, int $currentDepth = 1): array
{
    if ($maxItems < 1 || $currentDepth > $maxDepth) {
        return [];
    }

    $items = [];
    // Limit items at this level so total stays <= maxItems
    $itemCount = random_int(1, min(3, $maxItems));
    $remaining = $maxItems - $itemCount;

    for ($i = 0; $i < $itemCount; $i++) {
        $children = [];
        if ($currentDepth < $maxDepth && $remaining > 0 && random_int(0, 1) === 1) {
            // Allow children to consume some of remaining budget
            $childBudget = min($remaining, 3);
            $children = generateRandomTree($maxDepth, $childBudget, $currentDepth + 1);
            $remaining -= count(array_keys($children, $children, true)) ?: count($children);
        }
        $items[] = [
            'label' => "Item {$currentDepth}.{$i}",
            'children' => $children,
        ];
    }

    return $items;
}
