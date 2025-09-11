<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('admission-clerk')) {
            return redirect()->route('admission.dashboard');
        } elseif ($user->hasRole('trainer')) {
            return redirect()->route('trainer.dashboard');
        } elseif ($user->hasRole('trainee')) {
            return redirect()->route('trainee.dashboard');
        }

        return redirect('/');
    }
}