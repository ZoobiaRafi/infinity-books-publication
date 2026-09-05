<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
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
        'testimonial_title',
        'testimonial_quote',
        'testimonial_initials',
        'testimonial_name',
        'testimonial_date',
        'cta_eyebrow',
        'cta_heading',
        'cta_text',
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

    /**
     * The show page reads this as $service['testimonial']['quote'] etc. —
     * assembled from the flat testimonial_* columns so the admin can edit
     * each part as its own field.
     */
    protected function getTestimonialAttribute(): ?array
    {
        if (blank($this->testimonial_quote)) {
            return null;
        }

        return [
            'title' => $this->testimonial_title,
            'quote' => $this->testimonial_quote,
            'initials' => $this->testimonial_initials,
            'name' => $this->testimonial_name,
            'date' => $this->testimonial_date,
        ];
    }
}
