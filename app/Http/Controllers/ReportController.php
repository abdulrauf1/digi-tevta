<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;



class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_courses' => Course::count(),
            'total_trainers' => User::where('role', 'trainer')->count(),
            'total_trainees' => User::where('role', 'trainee')->count(),
            'total_enrollments' => Enrollment::count(),
        ];
        
        return view('admin.reports.index', compact('stats'));
    }
}