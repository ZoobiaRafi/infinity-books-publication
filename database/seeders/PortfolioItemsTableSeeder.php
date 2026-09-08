<?php

namespace Database\Seeders;

use App\Models\PortfolioItem;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Portfolio cards shown on the home page and /portfolio, grouped into 7
 * genre/category cards (3 each). Safe to re-run — upserts on title rather
 * than duplicating rows. Several categories reuse the same underlying
 * cover image where a distinct one wasn't available for every slot.
 */
class PortfolioItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        $serviceIds = Service::pluck('id', 'slug');

        foreach ($this->data() as $order => $row) {
            $row['order'] = $order + 1;
            $row['service_id'] = $serviceIds[$row['service_slug']] ?? null;
            unset($row['service_slug']);

            PortfolioItem::updateOrCreate(['title' => $row['title']], $row);
        }
    }

    private function data(): array
    {
        return [
            // Fiction
            [
                'title' => 'Fiction — Literary & Contemporary',
                'service_slug' => 'ghostwriting',
                'category_label' => 'Fiction',
                'subtitle' => 'Literary & Contemporary',
                'image' => 'assets/images/portfolio/portfolio-book-1.avif',
                'image_alt' => 'Fiction book cover — Literary & Contemporary',
            ],
            [
                'title' => 'Fiction — Mystery & Suspense',
                'service_slug' => 'ghostwriting',
                'category_label' => 'Fiction',
                'subtitle' => 'Mystery & Suspense',
                'image' => 'assets/images/portfolio/portfolio-book-2.avif',
                'image_alt' => 'Fiction book cover — Mystery & Suspense',
            ],
            [
                'title' => 'Fiction — Romance & Drama',
                'service_slug' => 'ghostwriting',
                'category_label' => 'Fiction',
                'subtitle' => 'Romance & Drama',
                'image' => 'assets/images/portfolio/portfolio-book-3.avif',
                'image_alt' => 'Fiction book cover — Romance & Drama',
            ],

            // Non-Fiction
            [
                'title' => 'Non-Fiction — Business & Self-Development',
                'service_slug' => 'editing-proofreading',
                'category_label' => 'Non-Fiction',
                'subtitle' => 'Business & Self-Development',
                'image' => 'assets/images/portfolio/portfolio-book-4.avif',
                'image_alt' => 'Non-Fiction book cover — Business & Self-Development',
            ],
            [
                'title' => 'Non-Fiction — Health & Wellness',
                'service_slug' => 'editing-proofreading',
                'category_label' => 'Non-Fiction',
                'subtitle' => 'Health & Wellness',
                'image' => 'assets/images/portfolio/portfolio-book-5.avif',
                'image_alt' => 'Non-Fiction book cover — Health & Wellness',
            ],
            [
                'title' => 'Non-Fiction — History & Politics',
                'service_slug' => 'editing-proofreading',
                'category_label' => 'Non-Fiction',
                'subtitle' => 'History & Politics',
                'image' => 'assets/images/portfolio/portfolio-book-6.avif',
                'image_alt' => 'Non-Fiction book cover — History & Politics',
            ],

            // Children's Books
            [
                'title' => "Children's Books — Illustrated Picture Books",
                'service_slug' => 'cover-design',
                'category_label' => "Children's Books",
                'subtitle' => 'Illustrated Picture Books',
                'image' => 'assets/images/portfolio/portfolio-book-7.avif',
                'image_alt' => "Children's Books book cover — Illustrated Picture Books",
            ],
            [
                'title' => "Children's Books — Early Readers",
                'service_slug' => 'cover-design',
                'category_label' => "Children's Books",
                'subtitle' => 'Early Readers',
                'image' => 'assets/images/portfolio/portfolio-book-8.avif',
                'image_alt' => "Children's Books book cover — Early Readers",
            ],
            [
                'title' => "Children's Books — Middle Grade Adventures",
                'service_slug' => 'cover-design',
                'category_label' => "Children's Books",
                'subtitle' => 'Middle Grade Adventures',
                'image' => 'assets/images/portfolio/portfolio-mockup-1.avif',
                'image_alt' => "Children's Books book cover — Middle Grade Adventures",
            ],

            // Horror
            [
                'title' => 'Horror — Thriller & Suspense',
                'service_slug' => 'publishing-distribution',
                'category_label' => 'Horror',
                'subtitle' => 'Thriller & Suspense',
                'image' => 'assets/images/portfolio/portfolio-horror-1.avif',
                'image_alt' => 'Horror book cover — Thriller & Suspense',
            ],
            [
                'title' => 'Horror — Supernatural & Gothic',
                'service_slug' => 'publishing-distribution',
                'category_label' => 'Horror',
                'subtitle' => 'Supernatural & Gothic',
                'image' => 'assets/images/portfolio/portfolio-horror-2.avif',
                'image_alt' => 'Horror book cover — Supernatural & Gothic',
            ],
            [
                'title' => 'Horror — Psychological Horror',
                'service_slug' => 'publishing-distribution',
                'category_label' => 'Horror',
                'subtitle' => 'Psychological Horror',
                'image' => 'assets/images/portfolio/portfolio-book-1.avif',
                'image_alt' => 'Horror book cover — Psychological Horror',
            ],

            // Audiobooks
            [
                'title' => 'Audiobooks — Full Audio Production',
                'service_slug' => 'audiobook-production',
                'category_label' => 'Audiobooks',
                'subtitle' => 'Full Audio Production',
                'image' => 'assets/images/portfolio/portfolio-mockup-2.avif',
                'image_alt' => 'Audiobooks book cover — Full Audio Production',
            ],
            [
                'title' => 'Audiobooks — Fiction Narration',
                'service_slug' => 'audiobook-production',
                'category_label' => 'Audiobooks',
                'subtitle' => 'Fiction Narration',
                'image' => 'assets/images/portfolio/portfolio-book-2.avif',
                'image_alt' => 'Audiobooks book cover — Fiction Narration',
            ],
            [
                'title' => 'Audiobooks — Non-Fiction Narration',
                'service_slug' => 'audiobook-production',
                'category_label' => 'Audiobooks',
                'subtitle' => 'Non-Fiction Narration',
                'image' => 'assets/images/portfolio/portfolio-book-3.avif',
                'image_alt' => 'Audiobooks book cover — Non-Fiction Narration',
            ],

            // Memoir
            [
                'title' => 'Memoir — Biography & Life Stories',
                'service_slug' => 'book-marketing',
                'category_label' => 'Memoir',
                'subtitle' => 'Biography & Life Stories',
                'image' => 'assets/images/portfolio/portfolio-book-4.avif',
                'image_alt' => 'Memoir book cover — Biography & Life Stories',
            ],
            [
                'title' => 'Memoir — Personal Essays',
                'service_slug' => 'book-marketing',
                'category_label' => 'Memoir',
                'subtitle' => 'Personal Essays',
                'image' => 'assets/images/portfolio/portfolio-book-5.avif',
                'image_alt' => 'Memoir book cover — Personal Essays',
            ],
            [
                'title' => 'Memoir — Family Legacy',
                'service_slug' => 'book-marketing',
                'category_label' => 'Memoir',
                'subtitle' => 'Family Legacy',
                'image' => 'assets/images/portfolio/portfolio-book-6.avif',
                'image_alt' => 'Memoir book cover — Family Legacy',
            ],

            // Illustration
            [
                'title' => 'Illustration — Cover Art & Design',
                'service_slug' => 'cover-design',
                'category_label' => 'Illustration',
                'subtitle' => 'Cover Art & Design',
                'image' => 'assets/images/portfolio/portfolio-mockup-1.avif',
                'image_alt' => 'Illustration — Cover Art & Design',
            ],
            [
                'title' => 'Illustration — Concept & Character Art',
                'service_slug' => 'cover-design',
                'category_label' => 'Illustration',
                'subtitle' => 'Concept & Character Art',
                'image' => 'assets/images/portfolio/portfolio-mockup-2.avif',
                'image_alt' => 'Illustration — Concept & Character Art',
            ],
            [
                'title' => 'Illustration — Interior Book Art',
                'service_slug' => 'cover-design',
                'category_label' => 'Illustration',
                'subtitle' => 'Interior Book Art',
                'image' => 'assets/images/portfolio/portfolio-book-7.avif',
                'image_alt' => 'Illustration — Interior Book Art',
            ],
        ];
    }
}
