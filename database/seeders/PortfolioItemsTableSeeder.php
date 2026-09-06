<?php

namespace Database\Seeders;

use App\Models\PortfolioItem;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * One-time migration of the original 6 hardcoded portfolio cards (formerly
 * inline in portfolio.blade.php) into the portfolio_items table, each
 * linked to the service it best represents. Safe to re-run — upserts on
 * title rather than duplicating rows.
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
            [
                'title' => 'Fiction — Literary & Contemporary',
                'service_slug' => 'ghostwriting',
                'category_label' => 'Fiction',
                'subtitle' => 'Literary & Contemporary',
                'image' => 'assets/images/book-fiction.jpg',
                'image_alt' => 'Fiction book cover — Literary & Contemporary',
            ],
            [
                'title' => 'Non-Fiction — Business & Self-Development',
                'service_slug' => 'editing-proofreading',
                'category_label' => 'Non-Fiction',
                'subtitle' => 'Business & Self-Development',
                'image' => 'assets/images/book-nonfiction.jpg',
                'image_alt' => 'Non-Fiction book cover — Business & Self-Development',
            ],
            [
                'title' => "Children's Books — Illustrated Picture Books",
                'service_slug' => 'cover-design',
                'category_label' => "Children's Books",
                'subtitle' => 'Illustrated Picture Books',
                'image' => 'assets/images/book-children.jpg',
                'image_alt' => "Children's Books book cover — Illustrated Picture Books",
            ],
            [
                'title' => 'Horror — Thriller & Suspense',
                'service_slug' => 'publishing-distribution',
                'category_label' => 'Horror',
                'subtitle' => 'Thriller & Suspense',
                'image' => 'assets/images/book-horror.jpg',
                'image_alt' => 'Horror book cover — Thriller & Suspense',
            ],
            [
                'title' => 'Audiobooks — Full Audio Production',
                'service_slug' => 'audiobook-production',
                'category_label' => 'Audiobooks',
                'subtitle' => 'Full Audio Production',
                'image' => 'assets/images/book-audio.jpg',
                'image_alt' => 'Audiobooks book cover — Full Audio Production',
            ],
            [
                'title' => 'Memoir — Biography & Life Stories',
                'service_slug' => 'book-marketing',
                'category_label' => 'Memoir',
                'subtitle' => 'Biography & Life Stories',
                'image' => 'assets/images/book-memoir.jpg',
                'image_alt' => 'Memoir book cover — Biography & Life Stories',
            ],
        ];
    }
}
