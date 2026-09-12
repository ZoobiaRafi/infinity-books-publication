<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class EmailAccount extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'email_address',
        'from_name',
        'imap_host',
        'imap_port',
        'imap_encryption',
        'imap_username',
        'imap_password',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'smtp_username',
        'smtp_password',
        'is_active',
        'last_synced_at',
        'last_sync_error',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    // Encrypted at rest (backed by APP_KEY) via manual accessor/mutator
    // pairs rather than the plain 'encrypted' cast, because the admin form
    // (see EncryptedPasswordHandler) always renders these fields blank and
    // a blank submission must leave the stored value untouched - a plain
    // cast would instead overwrite it with an encrypted empty string.
    public function setImapPasswordAttribute(?string $value): void
    {
        if (filled($value)) {
            $this->attributes['imap_password'] = Crypt::encryptString($value);
        }
    }

    public function getImapPasswordAttribute(?string $value): ?string
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setSmtpPasswordAttribute(?string $value): void
    {
        if (filled($value)) {
            $this->attributes['smtp_password'] = Crypt::encryptString($value);
        }
    }

    public function getSmtpPasswordAttribute(?string $value): ?string
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    // Excluded from array/JSON output entirely - this model gets rendered
    // in Blade views built directly against its attributes, and password
    // values must never round-trip to the browser under any circumstance,
    // not even inside a hidden field.
    protected $hidden = [
        'imap_password',
        'smtp_password',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function folders(): HasMany
    {
        return $this->hasMany(EmailFolder::class)->orderBy('order');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(EmailMessage::class);
    }

    public function unreadCount(): int
    {
        return $this->messages()->where('is_read', false)->count();
    }
}
