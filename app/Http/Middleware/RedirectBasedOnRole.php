<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->hasRole('admin')) {
                return redirect('/admin/dashboard');
            } elseif ($user->hasRole('admission-clerk')) {
                return redirect('/admission/dashboard');
            } elseif ($user->hasRole('trainer')) {
                return redirect('/trainer/dashboard');
            } elseif ($user->hasRole('trainee')) {
                return redirect('/trainee/dashboard');
            }
        }

        return $next($request);
    }
}
