<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('conversations');

        DB::table('menu_items')->where('route', 'admin.chats.index')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible — the chat module (widget, admin panel, tables) has
        // been removed in favor of Tawk.to. See git history if it's ever
        // needed again.
    }
};
