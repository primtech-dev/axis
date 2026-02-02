<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // authenticate via custom Form Request (LoginRequest)
        $request->authenticate();

        // regenerate session id
        $request->session()->regenerate();

        // if user must change password (flag on user), redirect ke form change password
        if (auth()->user()->must_change_password) {
            return redirect()->route('password.change')
                ->with('warning', 'Anda harus mengubah password default untuk keamanan akun Anda');
        }

        // Compute a safe fallback URL in case there is no intended URL and some named routes don't exist.
        // Prefer named route 'dashboard' > 'home' > root URL '/'
        $fallbackUrl = Route::has('dashboard')
            ? route('dashboard')
            : (Route::has('home') ? route('home') : url('/'));

        // redirect to intended (previous intended URL) or fallback.
        return redirect()->intended($fallbackUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
