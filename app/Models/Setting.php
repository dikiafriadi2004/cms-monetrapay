<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Cache key prefix for setting groups.
     */
    protected const CACHE_PREFIX = 'settings.';

    /**
     * Cache TTL in seconds (1 hour).
     */
    protected const CACHE_TTL = 3600;

    /**
     * Get a setting value by key and group.
     */
    public static function get(string $key, string $group = 'general', $default = null): ?string
    {
        $settings = static::getGroup($group);

        return $settings[$key] ?? $default;
    }

    /**
     * Get all settings for a given group as key-value pairs (cached).
     */
    public static function getGroup(string $group): array
    {
        return Cache::remember(self::CACHE_PREFIX.$group, self::CACHE_TTL, function () use ($group) {
            return static::where('group', $group)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Boot model events to clear cache when settings change.
     */
    protected static function booted(): void
    {
        $clear = function (Setting $setting) {
            Cache::forget(self::CACHE_PREFIX.$setting->group);
        };

        static::saved($clear);
        static::deleted($clear);
    }
}
