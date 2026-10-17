<?php

use App\Services\AdInsertionService;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->service = new AdInsertionService;
});

describe('AdInsertionService::calculateInsertPositions()', function () {
    it('returns positions at every Nth paragraph', function () {
        expect($this->service->calculateInsertPositions(10, 3))->toBe([3, 6, 9]);
    });

    it('returns single position when paragraph count equals interval', function () {
        expect($this->service->calculateInsertPositions(3, 3))->toBe([3]);
    });

    it('returns empty array when paragraph count is less than interval', function () {
        expect($this->service->calculateInsertPositions(2, 3))->toBe([]);
    });

    it('returns positions for interval of 2', function () {
        expect($this->service->calculateInsertPositions(6, 2))->toBe([2, 4, 6]);
    });

    it('returns empty array when paragraph count is zero', function () {
        expect($this->service->calculateInsertPositions(0, 3))->toBe([]);
    });

    it('returns empty array when interval is zero', function () {
        expect($this->service->calculateInsertPositions(10, 0))->toBe([]);
    });

    it('returns positions up to paragraph count only', function () {
        expect($this->service->calculateInsertPositions(5, 3))->toBe([3]);
    });

    it('handles interval of 1', function () {
        expect($this->service->calculateInsertPositions(4, 1))->toBe([1, 2, 3, 4]);
    });

    it('handles large interval with small paragraph count', function () {
        expect($this->service->calculateInsertPositions(5, 10))->toBe([]);
    });

    it('handles interval equal to paragraph count', function () {
        expect($this->service->calculateInsertPositions(5, 5))->toBe([5]);
    });
});

describe('AdInsertionService::insertAdsInContent()', function () {
    it('returns original content when no active ads', function () {
        $content = '<p>First paragraph.</p><p>Second paragraph.</p>';
        $ads = new Collection([]);

        expect($this->service->insertAdsInContent($content, $ads))->toBe($content);
    });

    it('returns original content when content is empty', function () {
        $ad = (object) ['code' => '<script>ad</script>', 'interval' => 2, 'image' => null, 'name' => 'Test Ad'];
        $ads = new Collection([$ad]);

        expect($this->service->insertAdsInContent('', $ads))->toBe('');
    });

    it('inserts ad after every Nth paragraph', function () {
        $content = '<p>Para 1</p><p>Para 2</p><p>Para 3</p><p>Para 4</p>';
        $ad = (object) ['code' => '<script>ad-code</script>', 'interval' => 2, 'image' => null, 'name' => 'Test Ad'];
        $ads = new Collection([$ad]);

        $result = $this->service->insertAdsInContent($content, $ads);

        expect($result)->toContain('<p>Para 2</p><div class="ad-slot ad-auto-insert"><script>ad-code</script></div>');
        expect($result)->toContain('<p>Para 4</p><div class="ad-slot ad-auto-insert"><script>ad-code</script></div>');
    });

    it('does not insert ads when paragraph count is less than interval', function () {
        $content = '<p>Para 1</p><p>Para 2</p>';
        $ad = (object) ['code' => '<script>ad-code</script>', 'interval' => 5, 'image' => null, 'name' => 'Test Ad'];
        $ads = new Collection([$ad]);

        $result = $this->service->insertAdsInContent($content, $ads);

        expect($result)->toBe($content);
    });

    it('preserves original paragraph content and order', function () {
        $content = '<p>First paragraph with <strong>bold</strong> text.</p><p>Second paragraph.</p><p>Third paragraph.</p>';
        $ad = (object) ['code' => '<script>ad</script>', 'interval' => 2, 'image' => null, 'name' => 'Test Ad'];
        $ads = new Collection([$ad]);

        $result = $this->service->insertAdsInContent($content, $ads);

        expect($result)->toContain('<p>First paragraph with <strong>bold</strong> text.</p>');
        expect($result)->toContain('<p>Second paragraph.</p>');
        expect($result)->toContain('<p>Third paragraph.</p>');
    });

    it('wraps ad in div with correct classes', function () {
        $content = '<p>Para 1</p><p>Para 2</p><p>Para 3</p>';
        $ad = (object) ['code' => '<script>ad-code</script>', 'interval' => 2, 'image' => null, 'name' => 'Test Ad'];
        $ads = new Collection([$ad]);

        $result = $this->service->insertAdsInContent($content, $ads);

        expect($result)->toContain('<div class="ad-slot ad-auto-insert"><script>ad-code</script></div>');
    });

    it('handles image-based ads', function () {
        $content = '<p>Para 1</p><p>Para 2</p><p>Para 3</p>';
        $ad = (object) ['code' => null, 'interval' => 2, 'image' => '/images/ad-banner.jpg', 'name' => 'Banner Ad'];
        $ads = new Collection([$ad]);

        $result = $this->service->insertAdsInContent($content, $ads);

        expect($result)->toContain('<div class="ad-slot ad-auto-insert"><img src="/images/ad-banner.jpg" alt="Banner Ad"></div>');
    });

    it('handles multiple ads with different intervals', function () {
        $content = '<p>P1</p><p>P2</p><p>P3</p><p>P4</p><p>P5</p><p>P6</p>';
        $ad1 = (object) ['code' => '<script>ad1</script>', 'interval' => 2, 'image' => null, 'name' => 'Ad 1'];
        $ad2 = (object) ['code' => '<script>ad2</script>', 'interval' => 3, 'image' => null, 'name' => 'Ad 2'];
        $ads = new Collection([$ad1, $ad2]);

        $result = $this->service->insertAdsInContent($content, $ads);

        // Ad1 at positions 2, 4, 6; Ad2 at positions 3, 6
        expect($result)->toContain('<p>P2</p><div class="ad-slot ad-auto-insert"><script>ad1</script></div>');
        expect($result)->toContain('<p>P3</p><div class="ad-slot ad-auto-insert"><script>ad2</script></div>');
        expect($result)->toContain('<p>P4</p><div class="ad-slot ad-auto-insert"><script>ad1</script></div>');
    });

    it('handles content with no paragraph tags', function () {
        $content = '<div>Some content without paragraphs</div>';
        $ad = (object) ['code' => '<script>ad</script>', 'interval' => 2, 'image' => null, 'name' => 'Test Ad'];
        $ads = new Collection([$ad]);

        $result = $this->service->insertAdsInContent($content, $ads);

        expect($result)->toBe($content);
    });

    it('handles paragraphs with attributes', function () {
        $content = '<p class="intro">Para 1</p><p id="main">Para 2</p><p data-section="end">Para 3</p>';
        $ad = (object) ['code' => '<script>ad</script>', 'interval' => 2, 'image' => null, 'name' => 'Test Ad'];
        $ads = new Collection([$ad]);

        $result = $this->service->insertAdsInContent($content, $ads);

        expect($result)->toContain('<p id="main">Para 2</p><div class="ad-slot ad-auto-insert"><script>ad</script></div>');
        // Original paragraphs preserved
        expect($result)->toContain('<p class="intro">Para 1</p>');
        expect($result)->toContain('<p data-section="end">Para 3</p>');
    });

    it('skips ads with null interval', function () {
        $content = '<p>Para 1</p><p>Para 2</p><p>Para 3</p>';
        $ad = (object) ['code' => '<script>ad</script>', 'interval' => null, 'image' => null, 'name' => 'Test Ad'];
        $ads = new Collection([$ad]);

        $result = $this->service->insertAdsInContent($content, $ads);

        expect($result)->toBe($content);
    });
});
