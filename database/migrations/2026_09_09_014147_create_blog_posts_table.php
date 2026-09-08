<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order')->default(0);
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category');
            $table->string('read_time');
            $table->text('excerpt');
            $table->text('meta_description');
            $table->string('cover');
            $table->longText('body');
            $table->string('cta_eyebrow');
            $table->string('cta_heading');
            $table->text('cta_text');
            $table->foreignId('cta_service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('cta_button_text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
