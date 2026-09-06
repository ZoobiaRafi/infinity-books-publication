<?php

namespace App\Providers;

use App\Models\Service;
use App\Voyager\FormFields\IconPickerHandler;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.header', function ($view) {
            $view->with('navServices', Service::orderBy('order')->orderBy('id')->get());
        });

        Voyager::addFormField(IconPickerHandler::class);
    }
}
