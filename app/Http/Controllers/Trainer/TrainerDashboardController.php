<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Trainee;
use App\Models\Attendance;
use App\Models\TraineeAssessmentFile;
use App\Models\EnrollmentSession;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrainerDashboardController extends Controller
{
    public function index()
    {
        $trainer = Auth::user()->trainer->first();
        
        // Check if trainer exists
        if (!$trainer) {
            return redirect()->back()->with('error', 'No trainer profile found.');
        }

        // Get current session (ongoing session)
        $currentSession = EnrollmentSession::where('status', 'ongoing')
            ->whereHas('enrollments.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->with(['enrollments.course'])
            ->withCount('enrollments')
            ->first();

        // Get all enrollment sessions for this trainer
        $enrollmentSessions = EnrollmentSession::whereHas('enrollments.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->with(['enrollments.course'])
            ->withCount('enrollments')
            ->orderBy('start_date', 'desc')
            ->get();

        // Get trainer's courses with enrollment counts
        $myCourses = Course::where('trainer_id', $trainer->id)
            ->withCount('enrollments')
            ->orderBy('name')
            ->get();

        // Calculate stats
        $stats = [
            'myCourses' => $myCourses->count(),
            'myTrainees' => Trainee::whereHas('enrollments.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })->distinct()->count(),
            'todayAttendance' => Attendance::whereDate('date', today())
                ->whereHas('enrollment.course', function($query) use ($trainer) {
                    $query->where('trainer_id', $trainer->id);
                })->count(),
            'pendingAssessments' => TraineeAssessmentFile::where('status', 'pending')
                ->whereHas('enrollment.course', function($query) use ($trainer) {
                    $query->where('trainer_id', $trainer->id);
                })->count(),
        ];

        // Get recent assessments
        $recentAssessments = TraineeAssessmentFile::with(['enrollment.trainee.user', 'enrollment.course'])
            ->whereHas('enrollment.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('trainer.dashboard', compact(
            'currentSession', 
            'enrollmentSessions', 
            'stats', 
            'recentAssessments',
            'myCourses'
        ));
    }
}