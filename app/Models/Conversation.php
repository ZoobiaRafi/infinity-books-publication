<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'session_id',
        'name',
        'email',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function unreadFromVisitorCount(): int
    {
        return $this->messages()
            ->where('sender', 'visitor')
            ->whereNull('read_at')
            ->count();
    }
}
