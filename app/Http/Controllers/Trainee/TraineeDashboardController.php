<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\TraineeAssessmentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraineeDashboardController extends Controller
{
    public function index()
    {
        // Get the first trainee from the collection
        $trainee = Auth::user()->trainee->first();
        
        // Check if trainee exists
        if (!$trainee) {
            return redirect()->back()->with('error', 'No trainee profile found.');
        }
        
        $traineeEnrollment = Enrollment::where('trainee_id',$trainee->id)->first();

        if($traineeEnrollment)
        {
            $stats = [
                'myCourses' => $trainee->courses()->count(),
                'attendanceRate' => $this->calculateAttendanceRate($trainee),
                'pendingAssessments' => TraineeAssessmentFile::whereHas('enrollment', function($query) use ($trainee) {
                    $query->where('trainee_id', $trainee->id);
                })->where('status', 'pending')->count(),
                'submittedAssessments' => TraineeAssessmentFile::whereHas('enrollment', function($query) use ($trainee) {
                    $query->where('trainee_id', $trainee->id);
                })->where('status', 'submitted')->count(),
            ];

            $myCourses = $trainee->courses()->with('trainer')->get();
            
            $recentAssessments = TraineeAssessmentFile::whereHas('enrollment', function($query) use ($trainee) {
                    $query->where('trainee_id', $trainee->id);
                })
                ->latest()
                ->take(5)
                ->get();

            return view('trainee.dashboard', compact('stats', 'myCourses', 'recentAssessments'));
        }

        

        return view('trainee.dashboard', compact('traineeEnrollment'));
    }

    private function calculateAttendanceRate($trainee)
    {
        // Get attendance through enrollment relationship
        $totalClasses = Attendance::whereHas('enrollment', function($query) use ($trainee) {
            $query->where('trainee_id', $trainee->id);
        })->count();
        
        $presentClasses = Attendance::whereHas('enrollment', function($query) use ($trainee) {
            $query->where('trainee_id', $trainee->id);
        })->where('status', 'present')->count();
            
        return $totalClasses > 0 ? round(($presentClasses / $totalClasses) * 100, 2) : 0;
    }
}