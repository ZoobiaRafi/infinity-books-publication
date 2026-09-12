<?php

use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');

Route::get('/services', [FrontendController::class, 'servicesIndex'])->name('services.index');
Route::get('/services/{slug}', [FrontendController::class, 'servicesShow'])->name('services.show');

Route::get('/portfolio', [FrontendController::class, 'portfolio'])->name('portfolio');
Route::get('/testimonials', [FrontendController::class, 'testimonials'])->name('testimonials');

Route::get('/blog', [FrontendController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{slug}', [FrontendController::class, 'blogShow'])->name('blog.show');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact.index');
Route::post('/contact', [FrontendController::class, 'contactStore'])->name('contact.store');

Route::get('/terms', [FrontendController::class, 'terms'])->name('terms');
Route::get('/privacy', [FrontendController::class, 'privacy'])->name('privacy');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(['prefix' => 'admin/mail', 'middleware' => ['admin.user'], 'as' => 'admin.mail.'], function () {
    Route::get('/', [MailController::class, 'index'])->name('index');
    Route::get('/{account}', [MailController::class, 'inbox'])->name('inbox');
    Route::get('/{account}/folder/{folder}', [MailController::class, 'inbox'])->name('folder');
    Route::get('/{account}/search', [MailController::class, 'search'])->name('search');
    Route::post('/{account}/refresh/{folder?}', [MailController::class, 'refresh'])->name('refresh');
    Route::get('/{account}/message/{message}', [MailController::class, 'show'])->name('message.show');
    Route::get('/{account}/message/{message}/attachment/{attachment}', [MailController::class, 'downloadAttachment'])->name('attachment.download');
    Route::get('/{account}/compose', [MailController::class, 'compose'])->name('compose');
    Route::get('/{account}/compose/{replyTo}', [MailController::class, 'compose'])->name('reply');
    Route::post('/{account}/send', [MailController::class, 'send'])->name('send');
});
