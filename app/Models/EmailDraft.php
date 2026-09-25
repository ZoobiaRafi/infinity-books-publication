<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailDraft extends Model
{
    protected $fillable = [
        'email_account_id',
        'in_reply_to_message_id',
        'reply_all',
        'to_addresses',
        'cc_addresses',
        'bcc_addresses',
        'subject',
        'body_html',
    ];

    protected $casts = [
        'reply_all' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(EmailAccount::class, 'email_account_id');
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(EmailMessage::class, 'in_reply_to_message_id');
    }
}
