<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_post_service', function (Blueprint $table) {
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->primary(['blog_post_id', 'service_id']);
        });

        // Superseded by the many-to-many related_services relationship above -
        // a blog post can now relate to several services, and the CTA button
        // that used cta_button_text was dropped in favor of a "Related Books"
        // section built from those related services' portfolio items.
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['cta_service_id']);
            $table->dropColumn(['cta_service_id', 'cta_button_text']);
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->foreignId('cta_service_id')->nullable()->after('cta_text')->constrained('services')->nullOnDelete();
            $table->string('cta_button_text')->after('cta_service_id');
        });

        Schema::dropIfExists('blog_post_service');
    }
};
