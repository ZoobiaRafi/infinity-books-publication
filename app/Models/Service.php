<?php

namespace App\Models;

use App\Models\Concerns\ResolvesUploadedImageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Service extends Model
{
    use ResolvesUploadedImageUrl;

    public function portfolioItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class)->orderBy('order');
    }

    protected static function booted(): void
    {
        static::saving(function (Service $service) {
            $source = filled($service->slug) ? $service->slug : $service->nav_title;

            if (filled($source)) {
                $service->slug = $service->uniqueSlugFrom($source);
            }
        });
    }

    /**
     * Server-side fallback for the admin's slugify JS (Voyager's `slugify`
     * form-field option, configured on the `slug` data_row) — covers a
     * blank slug (JS disabled, or the field left empty) as well as a
     * collision: if the slug (whether auto-filled or hand-typed) is already
     * taken by another service, appends -2, -3, etc. until it's unique.
     * Str::slug() is idempotent on an already-valid slug, so this is safe
     * to run on every save regardless of where the value came from.
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

    protected $fillable = [
        'order',
        'slug',
        'nav_title',
        'summary',
        'icon',
        'title',
        'title_accent',
        'meta_description',
        'lede',
        'included_heading',
        'included_text',
        'included_list',
        'image',
        'image_alt',
        'process_heading',
        'steps',
        'cta_eyebrow',
        'cta_heading',
        'cta_text',
        'faq_question_1',
        'faq_answer_1',
        'faq_question_2',
        'faq_answer_2',
        'faq_question_3',
        'faq_answer_3',
        'faq_question_4',
        'faq_answer_4',
        'faq_question_5',
        'faq_answer_5',
    ];

    /**
     * Stored as one bullet per line in the admin textarea; the show page
     * needs it as an array. Named distinctly from the `included_list` column
     * itself — Voyager's own edit/create form reads that raw column directly
     * to prefill the textarea, and would break if this accessor intercepted
     * it and returned an array instead of the raw string.
     */
    protected function getIncludedListArrayAttribute(): array
    {
        return collect(explode("\n", (string) $this->included_list))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Stored as "Step title|Step description" one per line in the admin
     * textarea; the show page needs it as a list of [title, description]
     * pairs. Named distinctly from the `steps` column for the same reason as
     * includedListArray above.
     */
    protected function getStepsArrayAttribute(): array
    {
        return collect(explode("\n", (string) $this->steps))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function ($line) {
                [$title, $description] = array_pad(explode('|', $line, 2), 2, '');

                return [trim($title), trim($description)];
            })
            ->values()
            ->all();
    }

    protected function getImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->image);
    }

    /**
     * Up to 5 question/answer pairs stored as flat faq_question_N /
     * faq_answer_N columns so the admin can edit each independently. A pair
     * is skipped entirely unless both its question and answer are filled in
     * — e.g. leaving pair 3 empty on a service with 5 pairs shows only 4.
     */
    protected function getFaqsAttribute(): array
    {
        $faqs = [];

        for ($i = 1; $i <= 5; $i++) {
            $question = $this->{"faq_question_{$i}"};
            $answer = $this->{"faq_answer_{$i}"};

            if (filled($question) && filled($answer)) {
                $faqs[] = ['question' => $question, 'answer' => $answer];
            }
        }

        return $faqs;
    }
}
