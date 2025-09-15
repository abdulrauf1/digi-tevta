<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\EnrollmentSession;
use App\Models\TraineeAssessmentFile;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrainerDashboardController extends Controller
{
    public function index()
    {
        $trainer = Auth::user()->trainer->first();
        
        // Get current session - fixed query
        $currentSession = EnrollmentSession::whereHas('enrollment.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with(['enrollment', 'enrollment.course'])
            ->withCount('enrollment')
            ->first();
            
        // Get trainer's courses with enrollments count
        $myCourses = Course::where('trainer_id', $trainer->id)
            ->withCount('enrollment')
            ->get();
            
        // Get all enrollment sessions for this trainer
        $enrollmentSessions = EnrollmentSession::whereHas('enrollment.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->withCount('enrollment')
            ->orderBy('start_date', 'desc')
            ->get();
            
        // Get recent assessments
        $recentAssessments = TraineeAssessmentFile::whereHas('enrollment.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->with(['enrollment.trainee.user', 'enrollment.session'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Prepare stats
        $stats = [
            'myCourses' => $myCourses->count(),
            'myTrainees' => $this->getMyTraineesCount($trainer),
            'todayAttendance' => $this->getTodayAttendanceCount($trainer),
            'pendingAssessments' => $this->getPendingAssessmentsCount($trainer),
        ];
        
        return view('trainer.dashboard', compact(
            'currentSession', 
            'myCourses', 
            'enrollmentSessions', 
            'recentAssessments', 
            'stats'
        ));
    }
    
    private function getMyTraineesCount($trainer)
    {
        return Enrollment::whereHas('course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->distinct('trainee_id')
            ->count('trainee_id');
    }
    
    private function getTodayAttendanceCount($trainer)
    {
        // Implementation depends on your attendance model structure
        // Assuming you have an Attendance model with date field and relationship to enrollment
        return \App\Models\Attendance::whereHas('enrollment.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->whereDate('date', today())
            ->count();
    }
    
    private function getPendingAssessmentsCount($trainer)
    {
        return TraineeAssessmentFile::whereHas('enrollment.course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->where('status', 'pending')
            ->count();
    }
}