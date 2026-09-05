<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Voyager's own 2017_11_26_013050_add_user_role_relationship migration calls
 * ->change() on role_id without re-declaring ->nullable(), which resets the
 * column to NOT NULL on modern Laravel/MySQL and breaks user creation
 * (including `php artisan voyager:admin --create`). This restores nullable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->change();
        });
    }
};
