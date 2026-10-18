<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'config',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active theme
     */
    public static function active()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Activate this theme
     */
    public function activate()
    {
        // Deactivate all other themes
        static::query()->update(['is_active' => false]);
        
        // Activate this theme
        $this->update(['is_active' => true]);
    }
}
