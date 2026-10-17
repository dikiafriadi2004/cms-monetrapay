<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlugService
{
    /**
     * Generate a URL-friendly slug from a title.
     *
     * - Convert to lowercase
     * - Replace spaces and special characters with hyphens
     * - Remove non-alphanumeric characters except hyphens
     * - Trim hyphens from start and end
     * - Collapse consecutive hyphens into one
     *
     * @param  string  $title  The title to convert to a slug
     * @return string The generated slug
     */
    public function generate(string $title): string
    {
        if (trim($title) === '') {
            return '';
        }

        // Convert to lowercase
        $slug = mb_strtolower($title, 'UTF-8');

        // Replace spaces and special characters with hyphens
        $slug = preg_replace('/[\s\W]+/u', '-', $slug);

        // Remove non-alphanumeric characters except hyphens
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

        // Collapse consecutive hyphens into one
        $slug = preg_replace('/-+/', '-', $slug);

        // Trim hyphens from start and end
        $slug = trim($slug, '-');

        return $slug;
    }

    /**
     * Ensure slug uniqueness within a model's table.
     *
     * Check if slug exists in the given model's table.
     * If it does, append -2, -3, etc. until a unique slug is found.
     *
     * @param  string  $slug  The base slug to check
     * @param  string  $modelClass  The Eloquent model class (e.g., Blog::class)
     * @param  int|null  $excludeId  Exclude this ID from uniqueness check (for updates)
     * @return string A unique slug
     */
    public function makeUnique(string $slug, string $modelClass, ?int $excludeId = null): string
    {
        $candidate = $slug;
        $counter = 2;

        while ($this->slugExists($candidate, $modelClass, $excludeId)) {
            $candidate = $slug.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    /**
     * Check if a slug already exists in the model's table.
     *
     * Uses withTrashed() if the model uses SoftDeletes to ensure
     * uniqueness even among soft-deleted records.
     *
     * @param  string  $slug  The slug to check
     * @param  string  $modelClass  The Eloquent model class
     * @param  int|null  $excludeId  ID to exclude from the check
     * @return bool True if the slug exists
     */
    protected function slugExists(string $slug, string $modelClass, ?int $excludeId = null): bool
    {
        /** @var Model $model */
        $model = new $modelClass;

        $query = $model->newQuery()->where('slug', $slug);

        // Use withTrashed if the model uses SoftDeletes
        if (in_array(SoftDeletes::class, class_uses_recursive($modelClass))) {
            // @phpstan-ignore-next-line Eloquent builders for SoftDeletes models provide withTrashed at runtime.
            $query->withTrashed();
        }

        // Exclude a specific ID (useful for update operations)
        if ($excludeId !== null) {
            $query->where($model->getKeyName(), '!=', $excludeId);
        }

        return $query->exists();
    }
}
