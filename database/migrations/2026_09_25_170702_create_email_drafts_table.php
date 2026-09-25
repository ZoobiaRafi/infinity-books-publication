<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_account_id')->constrained()->cascadeOnDelete();
            // Which message this was a reply/reply-all to, if any - kept so
            // reopening the draft later still shows the right quoted thread
            // context. Nulled (not cascaded) if the original message is
            // itself later removed from the admin panel.
            $table->foreignId('in_reply_to_message_id')->nullable()->constrained('email_messages')->nullOnDelete();
            $table->boolean('reply_all')->default(false);
            $table->text('to_addresses')->nullable();
            $table->text('cc_addresses')->nullable();
            $table->text('bcc_addresses')->nullable();
            $table->string('subject')->nullable();
            $table->longText('body_html')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_drafts');
    }
};
