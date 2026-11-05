<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['read_at' => 'datetime'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }

    public function toPublic(): array
    {
        return [
            'id' => $this->id,
            'sender' => $this->sender,
            'author' => $this->author,
            'body' => $this->body,
            'time' => $this->created_at?->format('H:i'),
        ];
    }
}
