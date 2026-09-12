<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailFolder extends Model
{
    protected $fillable = [
        'email_account_id',
        'name',
        'display_name',
        'role',
        'order',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(EmailAccount::class, 'email_account_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(EmailMessage::class);
    }
}
