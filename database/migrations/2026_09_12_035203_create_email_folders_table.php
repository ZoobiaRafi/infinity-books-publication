<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_account_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // raw IMAP folder path, e.g. "INBOX", "INBOX.Sent"
            $table->string('display_name'); // e.g. "Inbox", "Sent", "Drafts", "Trash"
            $table->string('role')->nullable(); // inbox, sent, drafts, trash, junk, archive, null = custom
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['email_account_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_folders');
    }
};
