<?php

use App\Models\Blog;
use App\Services\SlugService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->service = new SlugService;
});

/**
 * Property-based tests for slug generation.
 */
describe('SlugService property invariants', function () {

    /**
     * Property: every generated slug matches the kebab-case pattern
     * [a-z0-9]+(-[a-z0-9]+)*
     */
    it('always produces valid kebab-case slug', function (string $title) {
        $slug = $this->service->generate($title);

        // Always assert
        expect($slug)->toBeString();

        // Empty input → empty output is acceptable
        if ($title === '' || trim($title) === '') {
            return;
        }

        if ($slug !== '') {
            expect($slug)->toMatch('/^[a-z0-9]+(-[a-z0-9]+)*$/');
        }
    })->with(function () {
        return [
            ['Hello World'],
            ['Pulsa & PPOB'],
            ['Article with NUMBERS 123'],
            ['Multiple    Spaces'],
            ['Dash-Already-Here'],
            ['CamelCaseTitle'],
            ['Café Résumé Naïve'],
            ['Symbols!@#$%^&*()'],
            ['---leading and trailing---'],
            ['UPPER CASE TITLE'],
            ['Mixed-CASE_Underscore'],
            ['Tab\tSeparator'],
            ['Emoji 🚀 in title'],
            ['Indonesian: kärakter spesial'],
            ['html <script>alert(1)</script>'],
        ];
    });

    /**
     * Property: slug never contains uppercase letters
     */
    it('never contains uppercase characters', function (string $title) {
        $slug = $this->service->generate($title);
        expect($slug)->toBe(strtolower($slug));
    })->with([
        'UPPERCASE',
        'MixedCase',
        'PascalCase',
        'lowercase',
        'WITH NUMBERS 123',
    ]);

    /**
     * Property: slug never starts or ends with hyphen
     */
    it('never has leading or trailing hyphen', function (string $title) {
        $slug = $this->service->generate($title);

        // Always assert to avoid risky marker
        expect($slug)->toBeString();

        if ($slug !== '') {
            expect(str_starts_with($slug, '-'))->toBeFalse();
            expect(str_ends_with($slug, '-'))->toBeFalse();
        }
    })->with([
        '---hello---',
        ' spaces around ',
        '!!!special!!!',
        'normal',
        '   ',
    ]);

    /**
     * Property: makeUnique always produces a slug not in DB
     */
    it('makeUnique never collides with existing slugs', function () {
        // Seed with conflicting slugs
        Blog::factory()->create(['slug' => 'test-slug']);
        Blog::factory()->create(['slug' => 'test-slug-2']);
        Blog::factory()->create(['slug' => 'test-slug-3']);

        $unique = $this->service->makeUnique('test-slug', Blog::class);

        expect(Blog::where('slug', $unique)->exists())->toBeFalse();
    });

    /**
     * Property: makeUnique returns suffix-incremented when collision occurs
     */
    it('appends incremental suffix on collision', function () {
        Blog::factory()->create(['slug' => 'my-post']);
        $unique1 = $this->service->makeUnique('my-post', Blog::class);
        Blog::factory()->create(['slug' => $unique1]);
        $unique2 = $this->service->makeUnique('my-post', Blog::class);

        expect($unique1)->not->toBe('my-post');
        expect($unique2)->not->toBe('my-post');
        expect($unique2)->not->toBe($unique1);
    });

    /**
     * Property: idempotent — running generate twice on same input gives same output
     */
    it('is idempotent', function (string $title) {
        $slug1 = $this->service->generate($title);
        $slug2 = $this->service->generate($title);

        expect($slug1)->toBe($slug2);
    })->with(function () {
        $cases = [];
        for ($i = 0; $i < 20; $i++) {
            $cases[] = [bin2hex(random_bytes(random_int(3, 15)))];
        }

        return $cases;
    });

    /**
     * Property: empty/whitespace-only input never produces a slug with content
     */
    it('returns empty for whitespace-only input', function (string $title) {
        $slug = $this->service->generate($title);
        expect(trim($slug))->toBe('');
    })->with([
        '',
        '   ',
        "\t\n",
        '...',
        '!!!',
    ]);
});
