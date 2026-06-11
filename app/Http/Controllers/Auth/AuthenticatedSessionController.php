<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     * Jika sudah login sebagai user biasa → redirect ke dashboard.
     * Jika sudah login sebagai auditor → logout paksa dan tampilkan form login user.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (!Auth::user()->isAuditor()) {
                // Sudah login sebagai user biasa, langsung ke dashboard
                return redirect()->route('dashboard');
            }
            // Sudah login sebagai auditor, tapi minta halaman login user biasa
            // → logout paksa agar bisa login sebagai user biasa
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Cek secara proaktif sebelum otentikasi dijalankan
        $email = $request->input('email');
        $preCheckUser = \App\Models\User::where('email', $email)->first();

        if ($preCheckUser && $preCheckUser->isAuditor()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'Akun ini terdaftar sebagai Auditor. Silakan login melalui portal Auditor.',
            ]);
        }

        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user->onboarding_completed) {
            return redirect()->route('onboarding');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
