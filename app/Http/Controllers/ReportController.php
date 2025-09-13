<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Trainer;
use App\Models\Trainee;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Basic statistics
        $stats = [
            'total_courses' => Course::count(),
            'total_trainers' => Trainer::count(),
            'total_trainees' => Trainee::count(),
            'total_enrollments' => Enrollment::count(),
        ];
        
        // Enrollment trends (last 6 months)
        $enrollmentTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Enrollment::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $enrollmentTrends[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }
        
        // Course popularity (top 5)
        $popularCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();
            
        // Recent enrollments
        $recentEnrollments = Enrollment::with(['course', 'trainee'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        
        return view('admin.reports.index', compact(
            'stats', 
            'enrollmentTrends', 
            'popularCourses',
            'recentEnrollments'
        ));
    }
}