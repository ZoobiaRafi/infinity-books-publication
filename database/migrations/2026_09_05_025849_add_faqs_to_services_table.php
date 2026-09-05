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
        Schema::table('services', function (Blueprint $table) {
            for ($i = 1; $i <= 5; $i++) {
                $table->string("faq_question_{$i}")->nullable()->after('cta_text');
                $table->text("faq_answer_{$i}")->nullable()->after("faq_question_{$i}");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $columns = [];
            for ($i = 1; $i <= 5; $i++) {
                $columns[] = "faq_question_{$i}";
                $columns[] = "faq_answer_{$i}";
            }
            $table->dropColumn($columns);
        });
    }
};
