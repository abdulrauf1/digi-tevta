<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Trainee;
use App\Models\Attendance;
use App\Models\TraineeAssessmentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerController extends Controller
{
    //
    public function index()
    {
        
        $trainer = Auth::user()->trainer->first();
        
        // Check if trainer exists
        if (!$trainer) {
            return redirect()->back()->with('error', 'No trainer profile found.');
        }
        
        $stats = [
            'myCourses' => Course::where('trainer_id', $trainer->id)->count(),
            'myTrainees' => Trainee::whereHas('enrollments.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })->count(),
            'todayAttendance' => Attendance::whereDate('created_at', today())
                ->whereHas('enrollment.course', function($query) use ($trainer) {
                    $query->where('trainer_id', $trainer->id);
                })->count(),
            'pendingAssessments' => TraineeAssessmentFile::where('status', 'pending')
                ->whereHas('enrollment.course', function($query) use ($trainer) {
                    $query->where('trainer_id', $trainer->id);
                })->count(),
        ];

        // Use enrollments_count instead of trainees_count
        $myCourses = Course::withCount('enrollments')
                        ->where('trainer_id', $trainer->id)
                        ->get();
        
        $recentAssessments = TraineeAssessmentFile::with(['enrollment.trainee.user', 'enrollment.course'])
            ->whereHas('enrollment.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->latest()
            ->take(5)
            ->get();
            // dd($myCourses);
        // Remove the dd() before returning the view
        return view('trainer.dashboard', compact('stats', 'myCourses', 'recentAssessments'));
    }
}

