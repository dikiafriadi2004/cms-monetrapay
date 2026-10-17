<?php

use App\Services\ContentEnhancer;

beforeEach(function () {
    $this->enhancer = new ContentEnhancer;
});

describe('ContentEnhancer::enhance()', function () {
    it('returns empty string for null input', function () {
        expect($this->enhancer->enhance(null))->toBe('');
    });

    it('returns empty string for empty input', function () {
        expect($this->enhancer->enhance(''))->toBe('');
    });

    it('adds loading=lazy to img tags without it', function () {
        $html = '<p>Test <img src="x.jpg" alt="x"> end</p>';
        $result = $this->enhancer->enhance($html);

        expect($result)->toContain('loading="lazy"');
        expect($result)->toContain('decoding="async"');
    });

    it('does not duplicate loading attribute when already present', function () {
        $html = '<img src="x.jpg" loading="eager">';
        $result = $this->enhancer->enhance($html);

        // count occurrences of "loading="
        expect(substr_count($result, 'loading='))->toBe(1);
        expect($result)->toContain('loading="eager"');
    });

    it('adds rel=noopener to target=_blank anchors', function () {
        $html = '<a href="https://x.com" target="_blank">link</a>';
        $result = $this->enhancer->enhance($html);

        expect($result)->toContain('rel="noopener noreferrer"');
    });

    it('does not add rel when already present', function () {
        $html = '<a href="https://x.com" target="_blank" rel="nofollow">link</a>';
        $result = $this->enhancer->enhance($html);

        expect(substr_count($result, 'rel='))->toBe(1);
        expect($result)->toContain('rel="nofollow"');
    });

    it('preserves existing img attributes', function () {
        $html = '<img src="x.jpg" alt="hello" class="hero" width="800">';
        $result = $this->enhancer->enhance($html);

        expect($result)->toContain('src="x.jpg"');
        expect($result)->toContain('alt="hello"');
        expect($result)->toContain('class="hero"');
        expect($result)->toContain('width="800"');
    });

    it('handles multiple img tags', function () {
        $html = '<img src="a.jpg"><img src="b.jpg"><img src="c.jpg" loading="eager">';
        $result = $this->enhancer->enhance($html);

        expect(substr_count($result, 'loading='))->toBe(3);
        expect(substr_count($result, 'loading="lazy"'))->toBe(2);
        expect(substr_count($result, 'loading="eager"'))->toBe(1);
    });

    it('does not modify anchors without target=_blank', function () {
        $html = '<a href="/page">internal</a>';
        $result = $this->enhancer->enhance($html);

        expect($result)->not->toContain('rel=');
    });
});
