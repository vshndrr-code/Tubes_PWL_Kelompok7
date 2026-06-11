<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Mark the current user's onboarding as completed, create their first account,
     * and redirect to dashboard.
     */
    public function complete(Request $request): RedirectResponse
    {
        $request->validate([
            'currency'        => ['required', 'string', 'in:IDR,USD,EUR,SGD'],
            'initial_balance' => ['required', 'numeric', 'min:0', 'max:9999999999999'],
        ], [
            'initial_balance.max' => 'Angka yang dimasukkan terlalu besar.',
        ]);

        $user = Auth::user();

        // Create the initial cash account with the chosen currency & balance
        Account::create([
            'user_id' => $user->id,
            'name'    => 'Cash (' . $request->currency . ')',
            'type'    => 'cash',
            'balance' => $request->initial_balance,
        ]);

        $user->currency = $request->currency;
        $user->onboarding_completed = true;
        $user->save();

        return redirect()->route('dashboard');
    }
}
