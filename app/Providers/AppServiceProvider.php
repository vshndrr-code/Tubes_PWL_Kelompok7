<?php

namespace App\Providers;

use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $accounts = Account::where('user_id', Auth::id())
                    ->orderBy('name')
                    ->get();

                $selectedAccount = null;
                $selectedAccountId = session('selected_account_id');

                if (! request()->routeIs('accounts.index')) {
                    if ($selectedAccountId) {
                        $selectedAccount = $accounts->firstWhere('id', $selectedAccountId);
                    }

                    $selectedAccount = $selectedAccount ?? $accounts->first();
                }

                $view->with([
                    'accounts' => $accounts,
                    'selectedAccount' => $selectedAccount,
                ]);
            }
        });
    }
}
