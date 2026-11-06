<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'city',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'is_read',
        'admin_reply',
        'replied_at',
        'replied_by',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'replied_at' => 'datetime',
        ];
    }

    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function hasBeenReplied(): bool
    {
        return $this->replied_at !== null;
    }

    /**
     * Invalidate the unread count cache when a record changes.
     */
    protected static function booted(): void
    {
        $clear = fn () => Cache::forget('contacts.unread.count');
        static::saved($clear);
        static::deleted($clear);
    }
}
