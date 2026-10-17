<?php

use App\Services\SlugService;

beforeEach(function () {
    $this->slugService = new SlugService;
});

describe('SlugService::generate()', function () {
    it('converts a simple title to a slug', function () {
        expect($this->slugService->generate('Hello World'))->toBe('hello-world');
    });

    it('handles special characters and symbols', function () {
        expect($this->slugService->generate('Artikel Tentang PHP & Laravel!'))->toBe('artikel-tentang-php-laravel');
    });

    it('collapses multiple spaces into a single hyphen', function () {
        expect($this->slugService->generate('  Multiple   Spaces  '))->toBe('multiple-spaces');
    });

    it('trims leading and trailing hyphens', function () {
        expect($this->slugService->generate('---Leading-Trailing---'))->toBe('leading-trailing');
    });

    it('returns empty string for empty input', function () {
        expect($this->slugService->generate(''))->toBe('');
    });

    it('returns empty string for whitespace-only input', function () {
        expect($this->slugService->generate('   '))->toBe('');
    });

    it('handles numbers in the title', function () {
        expect($this->slugService->generate('Top 10 Tips for 2024'))->toBe('top-10-tips-for-2024');
    });

    it('handles titles with only special characters', function () {
        expect($this->slugService->generate('!@#$%^&*()'))->toBe('');
    });

    it('converts uppercase to lowercase', function () {
        expect($this->slugService->generate('ALL UPPERCASE TITLE'))->toBe('all-uppercase-title');
    });

    it('handles mixed case with numbers', function () {
        expect($this->slugService->generate('Laravel 12 Release Notes'))->toBe('laravel-12-release-notes');
    });
});
