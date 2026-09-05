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
            $table->dropColumn([
                'testimonial_title',
                'testimonial_quote',
                'testimonial_initials',
                'testimonial_name',
                'testimonial_date',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('testimonial_title')->nullable()->after('steps');
            $table->text('testimonial_quote')->nullable()->after('testimonial_title');
            $table->string('testimonial_initials')->nullable()->after('testimonial_quote');
            $table->string('testimonial_name')->nullable()->after('testimonial_initials');
            $table->string('testimonial_date')->nullable()->after('testimonial_name');
        });
    }
};
