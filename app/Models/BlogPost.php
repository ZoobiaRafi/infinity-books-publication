<?php

namespace App\Models;

use App\Models\Concerns\ResolvesUploadedImageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use ResolvesUploadedImageUrl;

    protected $fillable = [
        'order',
        'slug',
        'title',
        'category',
        'read_time',
        'excerpt',
        'meta_description',
        'cover',
        'body',
        'cta_eyebrow',
        'cta_heading',
        'cta_text',
    ];

    public function relatedServices(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'blog_post_service');
    }

    protected function getCoverUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->cover);
    }

    protected static function booted(): void
    {
        static::saving(function (BlogPost $post) {
            $source = filled($post->slug) ? $post->slug : $post->title;

            if (filled($source)) {
                $post->slug = $post->uniqueSlugFrom($source);
            }
        });
    }

    /**
     * Same fallback/collision-guard as Service::uniqueSlugFrom() — see the
     * comment there for why this runs on every save rather than only when
     * the slug is blank.
     */
    private function uniqueSlugFrom(string $source): string
    {
        $base = Str::slug($source);
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
