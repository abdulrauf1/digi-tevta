<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Course;
use App\Models\Trainee;
use App\Models\Trainer;
use App\Models\Attendance;
use App\Models\TraineeAssessmentFile;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
    public function index()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalCourses' => Course::count(),
            'totalTrainees' => Trainee::count(),
            'totalTrainers' => Trainer::count(),
            'pendingAssessments' => TraineeAssessmentFile::count(),
            'attendanceRate' => Attendance::whereDate('created_at', today())->count() / max(Trainee::count(), 1) * 100,
        ];

        $recentCourses = Course::with('trainer')->latest()->take(5)->get();
        $recentUsers = User::with('roles')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentCourses', 'recentUsers'));
    }
}

