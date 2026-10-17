<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $name
 * @property string $location
 * @property bool $is_active
 */
class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * Clear cached frontend menu when this menu changes.
     */
    protected static function booted(): void
    {
        $clear = function (Menu $menu) {
            Cache::forget('menu.location.'.$menu->location);
            // Also clear the previous location if it changed
            if ($menu->isDirty('location')) {
                Cache::forget('menu.location.'.$menu->getOriginal('location'));
            }
        };

        static::saved($clear);
        static::deleted($clear);
    }
}
