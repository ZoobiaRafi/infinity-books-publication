<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticPages = [
            ['route' => 'home', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['route' => 'about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['route' => 'services.index', 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['route' => 'portfolio', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['route' => 'testimonials', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['route' => 'blog.index', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['route' => 'contact.index', 'priority' => '0.7', 'changefreq' => 'yearly'],
            ['route' => 'terms', 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['route' => 'privacy', 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        $urls = collect($staticPages)->map(fn ($page) => [
            'loc' => route($page['route']),
            'lastmod' => now()->toAtomString(),
            'changefreq' => $page['changefreq'],
            'priority' => $page['priority'],
        ]);

        $services = Service::orderBy('order')->get()->map(fn (Service $service) => [
            'loc' => route('services.show', $service->slug),
            'lastmod' => $service->updated_at->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.9',
        ]);

        $posts = BlogPost::orderBy('order')->get()->map(fn (BlogPost $post) => [
            'loc' => route('blog.show', $post->slug),
            'lastmod' => $post->updated_at->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.7',
        ]);

        $urls = $urls->concat($services)->concat($posts);

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
