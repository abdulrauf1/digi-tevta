<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\EnrollmentSession;
use App\Models\TraineeAssessmentFile;
use App\Models\Attendance;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrainerCourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $trainer = Auth::user()->trainer->first();
        
        $courses = Course::where('trainer_id', $trainer->id)
            ->withCount('enrollment')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('trainer.courses.index', compact('courses'));
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        // Authorization - ensure trainer owns this course
        if ($course->trainer_id !== Auth::user()->trainer->first()->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $course->load(['enrollment.trainee.user', 'enrollment.enrollmentSession']);
        
        
        // Get attendance stats
        $attendanceStats = $this->getCourseAttendanceStats($course);
        
        // Get recent assessments
        $recentAssessments = TraineeAssessmentFile::whereHas('enrollment', function($query) use ($course) {
            $query->where('course_id', $course->id);
        })
        ->with('enrollment.trainee.user')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
        
        return view('trainer.courses.show', compact('course', 'attendanceStats', 'recentAssessments'));
    }

    /**
     * Show enrollments for a specific course.
     */
    public function enrollments(Course $course)
    {
        // Authorization - ensure trainer owns this course
        if ($course->trainer_id !== Auth::user()->trainer->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $enrollments = Enrollment::where('course_id', $course->id)
            ->with(['trainee.user', 'attendances', 'assessments'])
            ->get();
            
        // Calculate attendance percentage for each enrollment
        $enrollments->each(function($enrollment) {
            $totalSessions = Attendance::where('enrollment_id', $enrollment->id)->count();
            $presentSessions = Attendance::where('enrollment_id', $enrollment->id)
                ->where('status', 'present')
                ->count();
                
            $enrollment->attendance_percentage = $totalSessions > 0 
                ? round(($presentSessions / $totalSessions) * 100, 2) 
                : 0;
        });
        
        return view('trainer.courses.enrollments', compact('course', 'enrollments'));
    }

    /**
     * Get attendance statistics for a course.
     */
    private function getCourseAttendanceStats(Course $course)
    {
        $totalSessions = Attendance::whereHas('enrollment', function($query) use ($course) {
            $query->where('course_id', $course->id);
        })->distinct('date')->count('date');
        
        $presentCount = Attendance::whereHas('enrollment', function($query) use ($course) {
            $query->where('course_id', $course->id);
        })->where('status', 'present')->count();
        
        $absentCount = Attendance::whereHas('enrollment', function($query) use ($course) {
            $query->where('course_id', $course->id);
        })->where('status', 'absent')->count();
        
        $lateCount = Attendance::whereHas('enrollment', function($query) use ($course) {
            $query->where('course_id', $course->id);
        })->where('status', 'late')->count();
        
        $totalAttendanceRecords = $presentCount + $absentCount + $lateCount;
        $attendanceRate = $totalAttendanceRecords > 0 
            ? round(($presentCount / $totalAttendanceRecords) * 100, 2) 
            : 0;
            
        return [
            'total_sessions' => $totalSessions,
            'present_count' => $presentCount,
            'absent_count' => $absentCount,
            'late_count' => $lateCount,
            'attendance_rate' => $attendanceRate
        ];
    }
}