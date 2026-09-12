<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailMessage extends Model
{
    protected $fillable = [
        'email_account_id',
        'email_folder_id',
        'uid',
        'message_id',
        'in_reply_to',
        'references',
        'subject',
        'from_name',
        'from_email',
        'to',
        'cc',
        'date',
        'is_read',
        'is_flagged',
        'is_answered',
        'has_attachments',
        'snippet',
        'body_html',
        'body_text',
        'body_synced',
    ];

    protected $casts = [
        'to' => 'array',
        'cc' => 'array',
        'date' => 'datetime',
        'is_read' => 'boolean',
        'is_flagged' => 'boolean',
        'is_answered' => 'boolean',
        'has_attachments' => 'boolean',
        'body_synced' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(EmailAccount::class, 'email_account_id');
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(EmailFolder::class, 'email_folder_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(EmailAttachment::class);
    }
}
