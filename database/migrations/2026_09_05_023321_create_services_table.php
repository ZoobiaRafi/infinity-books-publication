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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order')->default(0);
            $table->string('slug')->unique();
            $table->string('nav_title');
            $table->text('summary');
            $table->text('icon')->nullable();

            $table->string('title');
            $table->string('title_accent')->nullable();
            $table->string('meta_description');
            $table->text('lede');

            $table->string('included_heading');
            $table->text('included_text');
            $table->text('included_list');

            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();

            $table->string('process_heading');
            $table->text('steps');

            $table->string('testimonial_title')->nullable();
            $table->text('testimonial_quote')->nullable();
            $table->string('testimonial_initials')->nullable();
            $table->string('testimonial_name')->nullable();
            $table->string('testimonial_date')->nullable();

            $table->string('cta_eyebrow');
            $table->string('cta_heading');
            $table->text('cta_text');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
