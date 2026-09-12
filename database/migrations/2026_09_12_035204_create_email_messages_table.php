<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('email_folder_id')->constrained()->cascadeOnDelete();

            // IMAP UID - unique per folder, used to detect new/removed
            // messages on sync without re-downloading everything.
            $table->unsignedBigInteger('uid');
            $table->string('message_id')->nullable(); // the Message-ID header
            $table->string('in_reply_to')->nullable(); // for threading
            $table->text('references')->nullable(); // space-separated Message-IDs, for threading

            $table->string('subject')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->text('to')->nullable(); // JSON array of {name, email}
            $table->text('cc')->nullable();
            $table->timestamp('date')->nullable();

            $table->boolean('is_read')->default(false);
            $table->boolean('is_flagged')->default(false);
            $table->boolean('is_answered')->default(false);
            $table->boolean('has_attachments')->default(false);

            $table->text('snippet')->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            $table->boolean('body_synced')->default(false); // body is fetched lazily on first open

            $table->timestamps();

            $table->unique(['email_folder_id', 'uid']);
            $table->index(['email_account_id', 'is_read']);
            $table->index('message_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
