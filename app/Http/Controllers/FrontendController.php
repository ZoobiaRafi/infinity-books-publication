<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmissionConfirmation;
use App\Mail\ContactSubmissionReceived;
use App\Models\BlogPost;
use App\Models\ContactSubmission;
use App\Models\PortfolioItem;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontendController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'active' => 'home',
            'portfolioItems' => PortfolioItem::orderBy('order')->get()->unique('category_label')->values(),
        ]);
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
        $portfolioItems = PortfolioItem::with('service')->orderBy('order')->get();

        return view('portfolio', [
            'active' => 'portfolio',
            'portfolioItems' => $portfolioItems,
            'categories' => $portfolioItems->pluck('category_label')->unique()->values(),
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
            'posts' => BlogPost::orderBy('order')->get(),
        ]);
    }

    public function blogShow(string $slug): View
    {
        $post = BlogPost::with('relatedServices')->where('slug', $slug)->first();

        if (! $post) {
            throw new NotFoundHttpException();
        }

        $relatedServiceIds = $post->relatedServices->pluck('id');

        return view('blog.show', [
            'active' => 'blog',
            'slug' => $slug,
            'post' => $post,
            'relatedBooks' => $relatedServiceIds->isEmpty()
                ? collect()
                : PortfolioItem::whereIn('service_id', $relatedServiceIds)->orderBy('order')->get(),
        ]);
    }

    public function contact(): View
    {
        return view('contact', ['active' => 'contact']);
    }

    public function contactStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[^<>]+$/'],
            'phone' => ['required', 'string', 'max:50', 'regex:/^[0-9+\-\s().]+$/'],
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'service' => ['required', 'string', Rule::in([
                'Ghostwriting',
                'Editing & Proofreading',
                'Cover Design & Illustration',
                'Publishing & Distribution',
                'Book Marketing',
                'Audiobook Production',
                'Not sure yet',
            ])],
            'message' => ['required', 'string', 'max:5000', 'regex:/^[^<>]+$/'],
        ], [
            'name.regex' => 'The name field may not contain HTML tags.',
            'phone.regex' => 'Please enter a valid phone number.',
            'message.regex' => 'The message field may not contain HTML tags.',
        ]);

        $submission = ContactSubmission::create($validated);

        $emailSent = false;

        try {
            Mail::to(config('mail.contact_notify_address'))->send(new ContactSubmissionReceived($submission));
            Mail::to($submission->email)->send(new ContactSubmissionConfirmation($submission));
            $emailSent = true;
        } catch (\Throwable $e) {
            // The submission is already saved — don't fail the request over a
            // mail delivery problem, just log it so it can be investigated.
            Log::error('Contact form email failed to send: '.$e->getMessage());
        }

        return back()
            ->with('success', $emailSent
                ? "Your email has been sent to our team. We'll reply to you shortly."
                : "We have received your details. Our team will contact you shortly.")
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
}
