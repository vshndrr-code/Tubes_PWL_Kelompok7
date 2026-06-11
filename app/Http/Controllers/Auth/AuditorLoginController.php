<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuditorLoginController extends Controller
{
    /**
     * Tampilkan formulir login khusus auditor.
     */
    public function create(): View
    {
        return view('auth.auditor-login');
    }

    /**
     * Proses autentikasi masuk auditor.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek apakah user memiliki hak akses auditor
            if ($user->isAuditor()) {
                return redirect()->intended(route('auditor.dashboard', absolute: false));
            }

            // Jika bukan auditor, segera keluarkan dan lempar error
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akses ditolak. Halaman login ini hanya dikhususkan untuk akun Auditor.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}
