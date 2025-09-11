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
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on role after login
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->hasRole('admission-clerk')) {
            return redirect()->intended(route('admission.dashboard'));
        } elseif ($user->hasRole('trainer')) {
            return redirect()->intended(route('trainer.dashboard'));
        } elseif ($user->hasRole('trainee')) {
            return redirect()->intended(route('trainee.dashboard'));
        }

        
        return redirect()->intended(route('dashboard')); // fallback

        // return redirect()->intended(route('dashboard', absolute: false));
        
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
