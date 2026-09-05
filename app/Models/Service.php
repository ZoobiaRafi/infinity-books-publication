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
