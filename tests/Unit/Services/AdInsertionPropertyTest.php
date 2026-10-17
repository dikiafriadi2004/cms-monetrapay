<?php

use App\Services\AdInsertionService;

beforeEach(function () {
    $this->service = new AdInsertionService;
});

/**
 * Property-based tests for ad insertion logic.
 * These tests run the same invariant across many random inputs.
 */
describe('AdInsertionService::calculateInsertPositions invariants', function () {

    /**
     * Property 1: result count = floor(paragraphCount / interval) when paragraphCount >= interval
     */
    it('produces correct count of insertion points', function (int $paragraphCount, int $interval) {
        $positions = $this->service->calculateInsertPositions($paragraphCount, $interval);

        if ($paragraphCount < $interval) {
            expect($positions)->toBeEmpty();
        } else {
            expect(count($positions))->toBe(intdiv($paragraphCount, $interval));
        }
    })->with(function () {
        $cases = [];
        for ($i = 0; $i < 50; $i++) {
            $cases[] = [random_int(0, 100), random_int(2, 10)];
        }

        return $cases;
    });

    /**
     * Property 2: every position is a multiple of interval
     */
    it('produces positions that are multiples of interval', function (int $paragraphCount, int $interval) {
        $positions = $this->service->calculateInsertPositions($paragraphCount, $interval);

        // Always assert something to avoid risky test marker
        expect($positions)->toBeArray();

        foreach ($positions as $pos) {
            expect($pos % $interval)->toBe(0);
        }
    })->with(function () {
        $cases = [];
        for ($i = 0; $i < 50; $i++) {
            $cases[] = [random_int(0, 100), random_int(2, 10)];
        }

        return $cases;
    });

    /**
     * Property 3: every position <= paragraphCount and > 0
     */
    it('produces positions within bounds', function (int $paragraphCount, int $interval) {
        $positions = $this->service->calculateInsertPositions($paragraphCount, $interval);

        expect($positions)->toBeArray();

        foreach ($positions as $pos) {
            expect($pos)->toBeGreaterThan(0);
            expect($pos)->toBeLessThanOrEqual($paragraphCount);
        }
    })->with(function () {
        $cases = [];
        for ($i = 0; $i < 50; $i++) {
            $cases[] = [random_int(0, 100), random_int(2, 10)];
        }

        return $cases;
    });

    /**
     * Property 4: positions are strictly increasing
     */
    it('produces strictly increasing positions', function (int $paragraphCount, int $interval) {
        $positions = $this->service->calculateInsertPositions($paragraphCount, $interval);

        expect($positions)->toBeArray();

        for ($i = 1; $i < count($positions); $i++) {
            expect($positions[$i])->toBeGreaterThan($positions[$i - 1]);
        }
    })->with(function () {
        $cases = [];
        for ($i = 0; $i < 50; $i++) {
            $cases[] = [random_int(0, 100), random_int(2, 10)];
        }

        return $cases;
    });

    /**
     * Property 5: empty when interval is 0 or negative
     */
    it('returns empty for non-positive interval', function (int $paragraphCount, int $interval) {
        $positions = $this->service->calculateInsertPositions($paragraphCount, $interval);
        expect($positions)->toBeEmpty();
    })->with([
        [10, 0], [10, -1], [50, -5], [0, 0], [100, -100],
    ]);

    /**
     * Property 6: spacing between consecutive positions is exactly interval
     */
    it('produces consistent spacing equal to interval', function (int $paragraphCount, int $interval) {
        $positions = $this->service->calculateInsertPositions($paragraphCount, $interval);

        expect($positions)->toBeArray();

        for ($i = 1; $i < count($positions); $i++) {
            expect($positions[$i] - $positions[$i - 1])->toBe($interval);
        }
    })->with(function () {
        $cases = [];
        for ($i = 0; $i < 30; $i++) {
            $cases[] = [random_int(10, 200), random_int(2, 10)];
        }

        return $cases;
    });
});

describe('AdInsertionService::insertAdsInContent invariants', function () {

    /**
     * Property: original paragraphs are preserved (none lost or modified)
     */
    it('preserves all original paragraphs', function () {
        $service = new AdInsertionService;

        // Build content with N paragraphs
        $count = random_int(3, 20);
        $paragraphs = [];
        for ($i = 1; $i <= $count; $i++) {
            $paragraphs[] = "<p>Paragraph {$i}</p>";
        }
        $content = implode('', $paragraphs);

        // Single ad with random interval
        $ad = (object) [
            'code' => '<span class="ad">AD</span>',
            'image' => null,
            'name' => 'Test',
            'interval' => random_int(2, 5),
        ];

        $result = $service->insertAdsInContent($content, collect([$ad]));

        // Every original paragraph should still appear
        for ($i = 1; $i <= $count; $i++) {
            expect($result)->toContain("<p>Paragraph {$i}</p>");
        }
    })->repeat(20);

    /**
     * Property: when no ads, content is unchanged
     */
    it('returns content unchanged with empty ads collection', function () {
        $service = new AdInsertionService;
        $content = '<p>One</p><p>Two</p><p>Three</p>';

        $result = $service->insertAdsInContent($content, collect([]));

        expect($result)->toBe($content);
    });

    /**
     * Property: empty content stays empty
     */
    it('returns empty string for empty content', function () {
        $service = new AdInsertionService;
        $ad = (object) ['code' => 'X', 'image' => null, 'interval' => 2];

        $result = $service->insertAdsInContent('', collect([$ad]));

        expect($result)->toBe('');
    });
});
