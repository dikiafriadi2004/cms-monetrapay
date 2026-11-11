<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageComponent extends Model
{
    protected $fillable = [
        'page_id',
        'component_type',
        'content',
        'settings',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'is_visible' => 'boolean',
    ];

    /**
     * Get the page that owns the component
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Scope for visible components
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope for ordered components
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
