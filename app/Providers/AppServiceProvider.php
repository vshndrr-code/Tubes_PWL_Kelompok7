<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Category;
use App\Policies\AccountPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\CategoryPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Account::class => AccountPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

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
        $this->registerPolicies();

        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $accounts = Account::where('user_id', Auth::id())
                    ->orderBy('name')
                    ->get();

                $selectedAccount = null;

                if (request()->routeIs(['accounts.show', 'accounts.edit'])) {
                    $routeAccount = request()->route('account');
                    if ($routeAccount instanceof Account) {
                        $selectedAccount = $routeAccount;
                    } elseif ($routeAccount) {
                        $selectedAccount = $accounts->firstWhere('id', $routeAccount);
                    }
                } elseif (request()->routeIs('accounts.index')) {
                    $selectedAccount = null;
                } else {
                    // Default to pinned account if exists, otherwise show all accounts (null)
                    $selectedAccount = $accounts->firstWhere('is_pinned', true);
                }

                $view->with([
                    'accounts' => $accounts,
                    'selectedAccount' => $selectedAccount,
                ]);
            }
        });
    }

    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
