<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, ?string $details = null): void
    {
        try {
            static::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'details' => $details,
                'ip' => request()->ip(),
            ]);
        } catch (\Throwable) {
        }
    }
}
