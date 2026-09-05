<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'services' => $this->services(),
        ]);
    }

    public function servicesShow(string $slug): View
    {
        $services = $this->services();

        if (! isset($services[$slug])) {
            throw new NotFoundHttpException();
        }

        return view('services.show', [
            'active' => 'services',
            'slug' => $slug,
            'service' => $services[$slug],
        ]);
    }

    public function portfolio(): View
    {
        return view('portfolio', ['active' => 'portfolio']);
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

        ContactSubmission::create($validated);

        return back()
            ->with('success', "Thanks! Your message has been received — we'll be in touch within one business day.")
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
     * Service content keyed by slug. Static marketing copy — kept here rather
     * than in the database since it isn't managed through Voyager BREAD.
     */
    private function services(): array
    {
        return [
            'ghostwriting' => [
                'nav_title' => 'Ghostwriting',
                'title' => 'Ghostwriting That Sounds Like ',
                'title_accent' => 'You',
                'meta_description' => 'Professional ghostwriting that captures your voice — memoir, business books and fiction, written to a schedule you can plan around.',
                'lede' => "Have a story in your head but no time to write it down? Our professional ghostwriters work in your voice, follow your outline, and deliver chapters on a schedule you can keep up with.",
                'included_heading' => 'From Outline to Finished Manuscript',
                'included_text' => "Whether you have a full outline, a stack of voice notes, or just a story you've been meaning to tell, our ghostwriters turn it into a publish-ready manuscript — memoir, business book, or fiction — without losing your voice along the way.",
                'included_list' => [
                    'In-depth author interviews to capture your voice and story',
                    'Chapter-by-chapter drafts on an agreed delivery schedule',
                    'Full-length manuscripts up to 30,000+ words',
                    'Unlimited revisions on select packages',
                    'A dedicated project manager throughout',
                    '100% ownership and confidentiality — your name, your rights',
                ],
                'image' => 'assets/images/hero-desk.jpg',
                'image_alt' => "An author's writing desk with manuscript, fountain pen and hardcover books",
                'process_heading' => 'Our Ghostwriting Process',
                'steps' => [
                    ['Discovery Call', 'We talk through your story, goals and timeline, then match you with a ghostwriter suited to your genre.'],
                    ['Outline & Voice Sample', 'Before a single chapter is drafted, we build a chapter outline and a short sample so you can confirm the voice feels right.'],
                    ['Drafting', "Chapters arrive on a set schedule with regular check-ins, so you're never waiting in the dark for progress."],
                    ['Review & Revision', 'You review the full manuscript and request changes — we refine until it reads exactly as you intended.'],
                    ['Handoff', 'You receive the final manuscript, fully yours, ready for editing and design.'],
                ],
                'testimonial' => [
                    'title' => 'Exceptional Editing & Publishing',
                    'quote' => 'The editors refined my story while keeping my voice intact. The whole publishing process was smooth and transparent.',
                    'initials' => 'PG',
                    'name' => 'Phillip G.D. Jones',
                    'date' => 'Mar 12, 2024',
                ],
                'cta_eyebrow' => 'Ready to Start Writing?',
                'cta_heading' => "Let's Get Your Story on the Page",
                'cta_text' => "Book a free consultation and we'll match you with the right ghostwriter for your genre.",
            ],
            'editing-proofreading' => [
                'nav_title' => 'Editing & Proofreading',
                'title' => 'Editing That Makes Agents ',
                'title_accent' => 'Say Yes',
                'meta_description' => "Developmental editing, line editing and proofreading that gets your manuscript to a professional, reader-ready standard.",
                'lede' => 'Even strong manuscripts get rejected over weak editing. Our editors handle developmental editing, line editing, copyediting and proofreading — line by line, chapter by chapter.',
                'included_heading' => 'Four Layers of Editorial Polish',
                'included_text' => 'We match your manuscript with editors who work in your genre, then move through structural, line and copy edits before a final proofread — so nothing gets missed.',
                'included_list' => [
                    'Developmental editing for structure, pacing and plot',
                    'Line editing for voice, clarity and flow',
                    'Copyediting for grammar, consistency and style',
                    'Final proofread before formatting and print',
                    'Editor notes and a marked-up manuscript, not just clean copy',
                    'A dedicated editor who stays with your book start to finish',
                ],
                'image' => 'assets/images/book-nonfiction.jpg',
                'image_alt' => 'A finished hardcover book ready for print',
                'process_heading' => 'Our Editing Process',
                'steps' => [
                    ['Manuscript Assessment', 'We read your full manuscript and flag the structural issues before touching a single sentence.'],
                    ['Developmental Edit', 'We address plot, pacing, character arcs or argument structure — the big-picture fixes that matter most.'],
                    ['Line & Copy Edit', 'Sentence-level editing for voice and clarity, followed by a grammar and consistency pass.'],
                    ['Author Review', 'You review every suggested change and approve or adjust before we move to final proofreading.'],
                    ['Final Proofread', 'One last pass immediately before formatting, catching anything introduced during revisions.'],
                ],
                'testimonial' => [
                    'title' => 'Great Support for First-Time Authors',
                    'quote' => 'The editorial team was patient, detailed and professional. I felt supported throughout the entire publishing journey.',
                    'initials' => 'MG',
                    'name' => 'Maria Garcia',
                    'date' => 'Oct 3, 2024',
                ],
                'cta_eyebrow' => 'Ready for a Second Pair of Eyes?',
                'cta_heading' => "Let's Polish Your Manuscript",
                'cta_text' => "Send us your draft and we'll recommend the right level of edit for where it stands today.",
            ],
            'cover-design' => [
                'nav_title' => 'Cover Design & Illustration',
                'title' => 'Covers Readers ',
                'title_accent' => 'Judge (and Buy)',
                'meta_description' => "Covers and interior artwork that capture your story at a glance — from children's picture books to literary fiction.",
                'lede' => "Covers and interior artwork that capture your story at a glance — from children's picture books to literary fiction, designed to stop the scroll on a bookstore shelf or an Amazon thumbnail.",
                'included_heading' => 'Design That Matches Your Genre',
                'included_text' => "A thriller cover and a picture book cover need completely different design instincts. We match your project with an illustrator or designer who works in your genre, then iterate until it's right.",
                'included_list' => [
                    'Custom front cover concepts, not template mockups',
                    'Full wraparound design for print (front, spine, back)',
                    'Interior formatting for print and e-book',
                    "Full-colour illustration for children's and picture books",
                    'Multiple concept rounds with revisions included',
                    'Print-ready files at trim size and bleed for any printer',
                ],
                'image' => 'assets/images/book-children.jpg',
                'image_alt' => "Illustrated children's book cover",
                'process_heading' => 'Our Design Process',
                'steps' => [
                    ['Creative Brief', "We talk genre, comp titles and mood — the covers you love and the ones you don't."],
                    ['Concept Round', 'Your designer presents two to three distinct cover directions to react to.'],
                    ['Refinement', "We narrow to one direction and refine typography, imagery and colour until it's launch-ready."],
                    ['Interior Formatting', 'Once the cover is locked, we format the interior for print and e-book to match.'],
                    ['Final Files', 'You receive print-ready and digital files sized correctly for every platform you publish on.'],
                ],
                'testimonial' => [
                    'title' => "Amazing Children's Book Illustration",
                    'quote' => 'The characters were vibrant and perfectly matched the tone of my story. Kids absolutely love the visuals.',
                    'initials' => 'ST',
                    'name' => 'Sarah Thompson',
                    'date' => 'Jun 15, 2024',
                ],
                'cta_eyebrow' => 'Ready to See Your Cover?',
                'cta_heading' => "Let's Design Something Readers Notice",
                'cta_text' => "Tell us about your book and genre — we'll match you with the right designer.",
            ],
            'publishing-distribution' => [
                'nav_title' => 'Publishing & Distribution',
                'title' => 'Published Everywhere ',
                'title_accent' => 'Readers Shop',
                'meta_description' => 'Formatting and release on Amazon, IngramSpark and other major platforms — print, e-book and beyond, with your ISBN assigned to you.',
                'lede' => 'Print, e-book, hardcover, paperback. We format your manuscript to industry standards, assign your ISBN, set up distribution, and put your book on every retailer that matters. You stay in control — we do the heavy lifting.',
                'included_heading' => 'Wherever Readers Buy Books',
                'included_text' => "We handle the technical side of getting a book to market — formatting, metadata, ISBN registration and retailer setup — so you go from finished file to available-for-purchase without the guesswork.",
                'included_list' => [
                    'Print and e-book formatting to retailer specifications',
                    'ISBN assignment, registered in your name',
                    'Amazon KDP, IngramSpark and Barnes & Noble setup',
                    'Global e-book distribution (Apple Books, Kobo, Google Play)',
                    'Metadata, categories and keyword optimisation',
                    'Author copies and print-on-demand set up for ongoing orders',
                ],
                'image' => 'assets/images/book-fiction.jpg',
                'image_alt' => 'A published novel cover',
                'process_heading' => 'Our Publishing Process',
                'steps' => [
                    ['Format Selection', 'We confirm which formats you want live — paperback, hardcover, e-book, or all three.'],
                    ['Technical Formatting', "Your final manuscript is formatted to each retailer's exact specifications for trim size and layout."],
                    ['ISBN & Metadata', 'We register your ISBN and write retailer-optimised titles, descriptions and keywords.'],
                    ['Platform Setup', "Your book goes live on Amazon, IngramSpark and the retailers you've chosen."],
                    ['Launch Check', 'We verify every listing looks right and order a proof copy before announcing your release.'],
                ],
                'testimonial' => [
                    'title' => 'Professional Audiobook Production',
                    'quote' => 'Narration, editing and production were handled perfectly. The audiobook opened a completely new audience for my book.',
                    'initials' => 'RL',
                    'name' => 'Robert Lee',
                    'date' => 'Oct 20, 2024',
                ],
                'cta_eyebrow' => 'Ready to Go Live?',
                'cta_heading' => "Let's Get Your Book on Shelves",
                'cta_text' => "Tell us which formats and platforms matter most to you — we'll map out the fastest path to launch.",
            ],
            'book-marketing' => [
                'nav_title' => 'Book Marketing',
                'title' => 'A Launch Plan, ',
                'title_accent' => 'Not Just a Launch Day',
                'meta_description' => 'Launch campaigns, social promotion and PR support that put your book in front of the right readers.',
                'lede' => 'Bespoke launch campaigns, social promotion and PR support that put your book in front of the right readers — before, during and after release day.',
                'included_heading' => 'Marketing Built Around Your Book',
                'included_text' => "A great book still needs readers to find it. We build a launch plan around your genre and audience, then run it — so you spend your time writing the next one, not chasing algorithms.",
                'included_list' => [
                    'Amazon listing optimisation (title, keywords, categories, A+ content)',
                    'Author website and landing page',
                    'Social media launch kit and content calendar',
                    'Press release and media/blogger outreach',
                    'Advance reader copy (ARC) distribution for early reviews',
                    'Email launch sequence for your subscriber list',
                ],
                'image' => 'assets/images/book-horror.jpg',
                'image_alt' => 'A book on a shelf ready for launch',
                'process_heading' => 'Our Marketing Process',
                'steps' => [
                    ['Audience & Positioning', 'We identify who your ideal reader is and how your book should be positioned against comp titles.'],
                    ['Launch Kit Build', 'Listing copy, social assets, press materials and email sequences are drafted ahead of release.'],
                    ['Pre-Launch Buzz', 'ARC distribution and outreach start building early reviews and momentum before release day.'],
                    ['Launch Week', 'Coordinated social, email and PR push around your release date to maximise first-week sales and rankings.'],
                    ['Post-Launch Momentum', 'We monitor performance and keep the campaign running so your book keeps finding readers after week one.'],
                ],
                'testimonial' => [
                    'title' => 'Powerful Book Marketing Strategy',
                    'quote' => "Their campaigns improved my book's visibility and boosted sales. I was impressed by their knowledge of the market.",
                    'initials' => 'MR',
                    'name' => 'Michael Rodriguez',
                    'date' => 'Jan 16, 2024',
                ],
                'cta_eyebrow' => 'Ready to Reach Readers?',
                'cta_heading' => "Let's Plan Your Launch",
                'cta_text' => "Tell us your release timeline and we'll build a campaign around it.",
            ],
            'audiobook-production' => [
                'nav_title' => 'Audiobook Production',
                'title' => 'Give Your Book ',
                'title_accent' => 'a Voice',
                'meta_description' => 'Professional narration, editing and mastering that brings your book to listeners on Audible, Apple Books and Spotify.',
                'lede' => 'Professional narration, editing and mastering that brings your book to listeners on Audible, Apple Books and Spotify — a growing audience many authors never reach.',
                'included_heading' => 'Studio-Quality Production',
                'included_text' => "We handle casting, direction, recording and post-production, so your audiobook meets the technical standards Audible and Apple Books require — without you needing a home studio.",
                'included_list' => [
                    'Narrator casting and voice sample selection',
                    'Professional studio recording and direction',
                    'Editing, levelling and noise reduction',
                    'ACX/Audible technical compliance mastering',
                    'Distribution to Audible, Apple Books and Spotify',
                    'Optional multi-voice production for fiction and dialogue-heavy books',
                ],
                'image' => 'assets/images/book-audio.jpg',
                'image_alt' => 'Audiobook cover art',
                'process_heading' => 'Our Audiobook Process',
                'steps' => [
                    ['Narrator Casting', 'We shortlist narrators suited to your genre and send voice samples for you to choose from.'],
                    ['Recording', 'Your narrator records in a professional studio, directed to match the tone of your book.'],
                    ['Editing & Mastering', 'Audio is edited for pacing and cleaned up to meet platform loudness and noise-floor requirements.'],
                    ['Quality Review', 'You review chapter samples and request any re-reads before final approval.'],
                    ['Distribution', 'We upload and configure your audiobook across Audible, Apple Books and Spotify.'],
                ],
                'testimonial' => [
                    'title' => 'Professional Audiobook Production',
                    'quote' => 'Narration, editing and production were handled perfectly. The audiobook opened a completely new audience for my book.',
                    'initials' => 'RL',
                    'name' => 'Robert Lee',
                    'date' => 'Oct 20, 2024',
                ],
                'cta_eyebrow' => 'Ready for Listeners?',
                'cta_heading' => "Let's Produce Your Audiobook",
                'cta_text' => "Tell us about your book and we'll recommend the right narrator and production path.",
            ],
        ];
    }

    /**
     * Blog post content keyed by slug. Static editorial copy for the same
     * reason as services() above.
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
