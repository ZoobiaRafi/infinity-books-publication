<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            // Nullable: an account can exist without being tied to a specific
            // staff login (admin still sees/manages it) - but the intended
            // model is one account per user.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('label');
            $table->string('email_address');
            $table->string('from_name')->nullable();

            $table->string('imap_host');
            $table->unsignedSmallInteger('imap_port')->default(993);
            $table->string('imap_encryption')->default('ssl'); // ssl, tls, none
            $table->string('imap_username');
            $table->text('imap_password'); // encrypted cast

            $table->string('smtp_host');
            $table->unsignedSmallInteger('smtp_port')->default(465);
            $table->string('smtp_encryption')->default('ssl'); // ssl, tls, none
            $table->string('smtp_username');
            $table->text('smtp_password'); // encrypted cast

            $table->boolean('is_active')->default(true);
            $table->timestamp('last_synced_at')->nullable();
            $table->text('last_sync_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
