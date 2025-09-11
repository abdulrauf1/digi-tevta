<?php
// app/Http\Controllers/Clerk/AdmissionController.php

namespace App\Http\Controllers\Clerk;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Trainee;
use App\Models\Trainer;
use App\Models\User;

class AdmissionController extends Controller
{
    public function index()
    {
        $stats = [
            'totalCourses' => Course::count(),
            'totalTrainees' => Trainee::count(),
            'totalTrainers' => Trainer::count(),
            // Count distinct trainees with pending enrollments
            'pendingApplications' => Trainee::whereHas('enrollments', function($query) {
                $query->where('status', 'pending');
            })->count(),
        ];

        $recentTrainees = Trainee::with('user')->latest()->take(5)->get();
        $recentTrainers = Trainer::with('user')->latest()->take(5)->get();

        return view('admission-clerk.dashboard', compact('stats', 'recentTrainees', 'recentTrainers'));
    }
}