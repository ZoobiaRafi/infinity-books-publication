<?php

use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

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

// Public chat widget endpoints (guest, session-scoped — no auth required)
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
Route::get('/chat/poll', [ChatController::class, 'poll'])->name('chat.poll');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(['prefix' => 'admin/chats', 'as' => 'admin.chats.', 'middleware' => ['web', 'admin.user']], function () {
    Route::get('/', [AdminChatController::class, 'index'])->name('index');
    Route::get('/unread-check', [AdminChatController::class, 'unreadCheck'])->name('unread-check');
    Route::get('/{conversation}', [AdminChatController::class, 'show'])->name('show');
    Route::get('/{conversation}/poll', [AdminChatController::class, 'poll'])->name('poll');
    Route::post('/{conversation}/reply', [AdminChatController::class, 'reply'])->name('reply');
    Route::post('/{conversation}/close', [AdminChatController::class, 'close'])->name('close');
});
