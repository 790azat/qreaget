<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    public const STATUSES = ['new', 'review', 'agreed', 'in_progress', 'done', 'rejected'];

    public const PRIORITIES = ['low', 'normal', 'high'];

    public const CLIENT_TYPES = ['individual', 'lawyer', 'company', 'importer', 'court', 'other'];

    protected $guarded = ['id'];

    protected $casts = ['deadline' => 'date', 'price' => 'decimal:2'];

    protected static function booted(): void
    {
        static::created(function (ServiceRequest $r) {
            if (! $r->number) {
                $r->number = 'QR-'.now()->format('y').'-'.str_pad((string) $r->id, 5, '0', STR_PAD_LEFT);
                $r->saveQuietly();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function events(): HasMany
    {
        return $this->hasMany(RequestEvent::class)->latest();
    }

    public function statusColor(): string
    {
        return [
            'new' => 'blue', 'review' => 'amber', 'agreed' => 'violet',
            'in_progress' => 'teal', 'done' => 'green', 'rejected' => 'red',
        ][$this->status] ?? 'gray';
    }
}
