<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmissionConfirmation;
use App\Mail\ContactSubmissionReceived;
use App\Models\ContactSubmission;
use App\Models\PortfolioItem;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontendController extends Controller
{
    public function home(): View
    {
        return view('home', ['active' => 'home']);
    }

    public function about(): View
    {
        return view('about', ['active' => 'about']);
    }

    public function servicesIndex(): View
    {
        return view('services.index', [
            'active' => 'services',
            'services' => Service::orderBy('order')->orderBy('id')->get(),
        ]);
    }

    public function servicesShow(string $slug): View
    {
        $service = Service::with('portfolioItems')->where('slug', $slug)->first();

        if (! $service) {
            throw new NotFoundHttpException();
        }

        return view('services.show', [
            'active' => 'services',
            'slug' => $slug,
            'service' => $service,
        ]);
    }

    public function portfolio(): View
    {
        return view('portfolio', [
            'active' => 'portfolio',
            'portfolioItems' => PortfolioItem::with('service')->orderBy('order')->get(),
            'services' => Service::orderBy('order')->orderBy('id')->get(),
        ]);
    }

    public function testimonials(): View
    {
        return view('testimonials', ['active' => 'testimonials']);
    }

    public function blogIndex(): View
    {
        return view('blog.index', [
            'active' => 'blog',
            'posts' => $this->blogPosts(),
        ]);
    }

    public function blogShow(string $slug): View
    {
        $posts = $this->blogPosts();

        if (! isset($posts[$slug])) {
            throw new NotFoundHttpException();
        }

        return view('blog.show', [
            'active' => 'blog',
            'slug' => $slug,
            'post' => $posts[$slug],
        ]);
    }

    public function contact(): View
    {
        return view('contact', ['active' => 'contact']);
    }

    public function contactStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $submission = ContactSubmission::create($validated);

        try {
            Mail::to(config('mail.contact_notify_address'))->send(new ContactSubmissionReceived($submission));
            Mail::to($submission->email)->send(new ContactSubmissionConfirmation($submission));
        } catch (\Throwable $e) {
            // The submission is already saved — don't fail the request over a
            // mail delivery problem, just log it so it can be investigated.
            Log::error('Contact form email failed to send: '.$e->getMessage());
        }

        return back()
            ->with('success', "We have received your details. Our team will contact you shortly.")
            ->withInput();
    }

    public function terms(): View
    {
        return view('terms', ['active' => null]);
    }

    public function privacy(): View
    {
        return view('privacy', ['active' => null]);
    }

    /**
     * Blog post content keyed by slug. Static editorial copy — not yet
     * managed through Voyager BREAD.
     */
    private function blogPosts(): array
    {
        return [
            'choosing-the-right-ghostwriter' => [
                'category' => 'Ghostwriting',
                'read_time' => '6 min read',
                'title' => 'Choosing the Right Ghostwriter for Your Story',
                'excerpt' => 'Five questions to ask before you hire someone to write your book — and the red flags that mean you should keep looking.',
                'meta_description' => 'Five questions to ask before you hire a ghostwriter — and the red flags that mean you should keep looking.',
                'cover' => 'assets/images/blog-ghostwriter.svg',
                'body' => <<<'HTML'
                    <p>Hiring a ghostwriter means handing someone your story before it exists on the page — which makes the decision feel riskier than it should. Most of that risk disappears once you know what to actually check before signing on with someone.</p>

                    <h2>1. Ask for a paid sample, not a free one</h2>
                    <p>A short paid trial chapter tells you more than a portfolio ever will. It shows you exactly how this writer handles your specific material, in your voice, on your timeline — not their best-case example from a different project entirely.</p>

                    <h2>2. Confirm who owns the manuscript</h2>
                    <p>This should be settled in writing before a single word is drafted. You want full ownership of the manuscript and all rights to it, with the ghostwriter's role kept confidential unless you choose otherwise.</p>

                    <blockquote>A good ghostwriting agreement reads like a work-for-hire contract, not a co-authorship one.</blockquote>

                    <h2>3. Ask how revisions are handled</h2>
                    <p>Find out upfront whether revision rounds are unlimited, capped, or billed separately. This single detail affects your budget more than almost anything else in the process.</p>

                    <h2>4. Look for genre-specific experience</h2>
                    <p>A ghostwriter who's excellent at business books isn't automatically right for a thriller. Ask what they've written most recently in your genre, and request a sample from that category specifically.</p>

                    <h2>5. Get a delivery schedule in writing</h2>
                    <p>Chapter-by-chapter deadlines, not a single vague "a few months," keep both sides accountable. If a ghostwriter won't commit to a schedule before you sign, that's worth noting.</p>

                    <h3>Red flags to watch for</h3>
                    <ul>
                        <li>Reluctance to put ownership and confidentiality terms in writing</li>
                        <li>No sample chapter or writing test offered before a full commitment</li>
                        <li>Vague answers about timeline or revision policy</li>
                        <li>Pressure to pay the full project fee upfront</li>
                    </ul>

                    <p>The right ghostwriter should feel less like you're outsourcing your book and more like you've found a collaborator who happens to be faster at getting your ideas onto the page than you are. Take the time to check these five things, and the rest of the process gets a lot easier.</p>
                    HTML,
                'cta_eyebrow' => 'Have a Story to Tell?',
                'cta_heading' => "Let's Find Your Ghostwriter",
                'cta_text' => 'Every writer on our team is vetted, genre-matched and available for a paid sample chapter first.',
                'cta_service_slug' => 'ghostwriting',
                'cta_button_text' => 'Explore Ghostwriting Services',
            ],
            'traditional-vs-self-publishing' => [
                'category' => 'Publishing',
                'read_time' => '8 min read',
                'title' => 'Traditional vs. Self-Publishing: What Actually Matters',
                'excerpt' => 'Rights, royalties, timelines and control — a clear-eyed comparison to help you pick the right path for your book.',
                'meta_description' => 'Rights, royalties, timelines and control — a clear-eyed comparison to help you pick the right publishing path for your book.',
                'cover' => 'assets/images/blog-publishing.svg',
                'body' => <<<'HTML'
                    <p>The traditional-versus-self-publishing debate usually gets framed as a status question — which one is "more legitimate." That's the wrong lens. The better question is which model actually fits your book, your timeline and your goals.</p>

                    <h2>Timeline</h2>
                    <p>Traditional publishing typically runs 18–24 months from a signed deal to a book on shelves, once you account for agent submission, editing rounds and the publisher's own schedule. Self-publishing can go from finished manuscript to live listing in a matter of weeks, because you control every deadline.</p>

                    <h2>Rights and royalties</h2>
                    <p>Traditional deals usually mean an advance against royalties, typically in the 8–15% range on net sales, and the publisher holds the rights for the contract term. Self-publishing keeps rights with you and typically pays 35–70% royalties, depending on the platform and price point, but there's no advance to fall back on.</p>

                    <blockquote>Neither model is "safer." Traditional trades speed and control for an advance and distribution support. Self-publishing trades that support for speed, control and a larger royalty share.</blockquote>

                    <h2>Control</h2>
                    <p>In a traditional deal, the publisher typically has final say on the cover, title and release date. Self-publishing puts every one of those decisions in your hands — which is either a feature or a burden depending on how much you want to manage.</p>

                    <h2>Distribution and marketing</h2>
                    <p>This is where the gap has narrowed the most. Traditional publishers still have stronger relationships for physical bookstore placement, but platforms like Amazon KDP and IngramSpark now put self-published books in front of the same online buyers, and marketing is increasingly the author's job either way.</p>

                    <h3>A simple way to decide</h3>
                    <ul>
                        <li>Want a faster path to market and to keep control of every decision? Self-publishing likely fits better.</li>
                        <li>Have a project that depends on bookstore placement, an advance, or a specific imprint's reputation? Traditional is worth pursuing.</li>
                        <li>Not sure yet? Many authors query traditionally while self-publishing a related shorter work to build an audience in parallel.</li>
                    </ul>

                    <p>There's no universally "correct" path — only the one that matches what you actually need from this particular book.</p>
                    HTML,
                'cta_eyebrow' => 'Not Sure Which Path Fits?',
                'cta_heading' => "Let's Map It Out Together",
                'cta_text' => "We'll walk through your goals and recommend the publishing route that fits your book.",
                'cta_service_slug' => 'publishing-distribution',
                'cta_button_text' => 'Explore Publishing Services',
            ],
            'book-launch-marketing-checklist' => [
                'category' => 'Marketing',
                'read_time' => '7 min read',
                'title' => 'The Book Launch Marketing Checklist',
                'excerpt' => 'What to do 90 days out, 30 days out and on release day — a practical timeline for a launch that actually sells books.',
                'meta_description' => 'What to do 90 days out, 30 days out and on release day — a practical timeline for a book launch that actually sells books.',
                'cover' => 'assets/images/blog-marketing.svg',
                'body' => <<<'HTML'
                    <p>Release day sales are mostly decided before release day arrives. By the time your book goes live, the reviews, the mailing list and the retailer algorithm signals should already be working in your favour. Here's the timeline that makes that happen.</p>

                    <h2>90 days out</h2>
                    <ul>
                        <li>Finalise your back-cover copy, categories and keywords — these drive discoverability from day one</li>
                        <li>Start building or growing an email list of readers interested in your genre</li>
                        <li>Line up 10–15 advance reader copy (ARC) readers for early reviews</li>
                        <li>Set up your author website and social profiles if you haven't already</li>
                    </ul>

                    <h2>30 days out</h2>
                    <ul>
                        <li>Send ARCs to your review list with a clear, specific request: post on release day</li>
                        <li>Schedule a content calendar covering the four weeks around launch</li>
                        <li>Reach out to podcasts, newsletters or bloggers in your genre for release-week features</li>
                        <li>Confirm your retailer listings are live and formatted correctly ahead of time</li>
                    </ul>

                    <h2>7 days out</h2>
                    <ul>
                        <li>Remind your email list and ARC readers that launch day is approaching</li>
                        <li>Prepare your launch-day social posts in advance so nothing is written last-minute</li>
                        <li>Double-check pre-order links and pricing across every platform</li>
                    </ul>

                    <blockquote>Reviews posted in the first 48 hours matter more to retailer algorithms than reviews that trickle in over months. Front-load them.</blockquote>

                    <h2>Release day</h2>
                    <ul>
                        <li>Send your launch email and post across every social channel</li>
                        <li>Ask your ARC readers and early supporters to post their reviews today, not "sometime this week"</li>
                        <li>Monitor your listing for formatting or metadata issues and fix immediately</li>
                    </ul>

                    <h2>The first 30 days after launch</h2>
                    <p>Momentum doesn't stop after week one. Keep posting reader reviews and quotes, consider a short-term ad push on the retailer your book is performing best on, and start reaching out for longer-form press or podcast coverage now that you have a published book — not just a manuscript — to talk about.</p>

                    <p>None of this requires a huge budget. It requires a plan you actually follow, starting well before release day rather than scrambling the week of.</p>
                    HTML,
                'cta_eyebrow' => 'Planning a Launch?',
                'cta_heading' => 'Let Us Build Your Marketing Timeline',
                'cta_text' => "We'll build and run this checklist for you, end to end.",
                'cta_service_slug' => 'book-marketing',
                'cta_button_text' => 'Explore Marketing Services',
            ],
        ];
    }
}
