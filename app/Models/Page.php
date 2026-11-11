<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $content
 * @property string|null $featured_image
 * @property string $template
 * @property string $status
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property int $order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'content',
        'use_page_builder',
        'layout_type',
        'featured_image',
        'template',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'order',
        'user_id',
        'gjs_css',
        'gjs_components',
        'gjs_styles',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'use_page_builder' => 'boolean',
            'gjs_components' => 'string',
            'gjs_css'        => 'string',
            'gjs_styles'     => 'string',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    /**
     * Get the author of the page
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the components for the page
     */
    public function components()
    {
        return $this->hasMany(PageComponent::class)->ordered();
    }

    /**
     * Get visible components only
     */
    public function visibleComponents()
    {
        return $this->hasMany(PageComponent::class)->visible()->ordered();
    }
}
