<?php

namespace App\Providers;

use App\Models\EmailAccount;
use App\Models\Service;
use App\Voyager\FormFields\EncryptedPasswordHandler;
use App\Voyager\FormFields\IconPickerHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        Voyager::addFormField(EncryptedPasswordHandler::class);

        View::composer('voyager::index', function ($view) {
            $user = Auth::user();

            if (! $user) {
                return;
            }

            $accounts = $user->hasRole('admin')
                ? EmailAccount::orderBy('label')->get()
                : EmailAccount::where('user_id', $user->id)->orderBy('label')->get();

            $accounts->each(function (EmailAccount $account) {
                $inboxCounts = DB::table('email_folders')
                    ->join('email_messages', 'email_messages.email_folder_id', '=', 'email_folders.id')
                    ->where('email_folders.email_account_id', $account->id)
                    ->where('email_folders.role', 'inbox')
                    ->selectRaw('count(*) as total, sum(is_read = 0) as unread, sum(is_read = 1) as opened')
                    ->first();

                $account->inbox_total = (int) ($inboxCounts->total ?? 0);
                $account->unread_count = (int) ($inboxCounts->unread ?? 0);
                $account->opened_count = (int) ($inboxCounts->opened ?? 0);
            });

            $view->with('mailAccounts', $accounts);
        });
    }
}
