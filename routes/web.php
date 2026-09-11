<?php

use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

// No physical public/favicon.ico exists on purpose - a static file at that
// exact path would be served directly by the webserver and this route would
// never be reached. Redirecting keeps the brand PNG as the single source of
// truth instead of a separately-generated .ico that could go stale.
Route::get('/favicon.ico', fn () => redirect(asset('assets/favicon-48.png'), 301));

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
