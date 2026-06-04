<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Budgeting;
use App\Policies\AccountPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\BudgetingPolicy;
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
        Budgeting::class => BudgetingPolicy::class,
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

    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
