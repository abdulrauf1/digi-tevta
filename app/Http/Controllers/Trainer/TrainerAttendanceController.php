<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentSession;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Models\LeaveDay;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrainerAttendanceController extends Controller
{
    /**
     * Show the form for selecting a course to take attendance
     */
    public function selectCourse(Request $request)
    {
        $trainer = Auth::user()->trainer->first();
        
        // Get courses taught by this trainer
        $courses = Course::where('trainer_id', $trainer->id)
            ->with(['sessions' => function($query) {
                $query->where('start_date', '<=', today())
                    ->where('end_date', '>=', today())
                    ->orderBy('name');
            }])
            ->get();
            
        return view('trainer.attendance.select-course', compact('courses'));
    }
    
    /**
     * Show the form for creating attendance for a session
     */
    public function create(Request $request, EnrollmentSession $session)
    {
        // Get the course ID from the query parameter
        $courseId = $request->query('course');
        
        // Verify the trainer has access to this session
        $trainer = Auth::user()->trainer->first();
        
        if (!$session->enrollment()->whereHas('course', function($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })->exists()) {
            abort(403, 'Unauthorized access to this session.');
        }
        
        // Get enrollments for this session, optionally filtered by course
        $enrollmentsQuery = $session->enrollment()->with('trainee.user');
        
        if ($courseId) {
            $enrollmentsQuery->where('course_id', $courseId);
        }
        
        $enrollments = $enrollmentsQuery->get();
        
        // Get the course for breadcrumbs and display
        $course = null;
        if ($courseId) {
            $course = Course::find($courseId);
        }
        
        // Check if attendance already exists for today
        $todayAttendance = Attendance::whereIn('enrollment_id', $enrollments->pluck('id'))
            ->whereDate('date', today())
            ->get()
            ->keyBy('enrollment_id');
            
        return view('trainer.attendance.create', compact('session', 'enrollments', 'todayAttendance', 'course'));
    }
    
    /**
     * Store attendance for a session
     */
    public function store(Request $request, EnrollmentSession $session)
    {
        $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:Present,Absent,Late,Leave',
            'remarks' => 'nullable|array',
            'remarks.*' => 'nullable|string|max:255'
        ]);
        
        $trainer = Auth::user()->trainer->first();
        
        // Verify the trainer has access to this session
        if (!$session->enrollment()->whereHas('course', function($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })->exists()) {
            abort(403, 'Unauthorized access to this session.');
        }
        
        // Get all enrollments for this session
        $enrollmentIds = $session->enrollment()->pluck('id');
        
        // Process attendance
        foreach ($request->attendance as $enrollmentId => $status) {
            // Verify enrollment belongs to this session
            if (!$enrollmentIds->contains($enrollmentId)) {
                continue;
            }
            
            // Create or update attendance record
            Attendance::updateOrCreate(
                [
                    'enrollment_id' => $enrollmentId,
                    'date' => today()
                ],
                [
                    'status' => $status,
                    'remarks' => $request->remarks[$enrollmentId] ?? null,
                ]
            );
        }

        return redirect()->route('trainer.attendance.session', $session)
            ->with('success', 'Attendance marked successfully.');
    }
    
    
    /**
     * Show attendance for a specific session
     */
    public function sessionAttendance(EnrollmentSession $session)
    {
        $trainer = Auth::user()->trainer->first();
        
        // Verify the trainer has access to this session
        if (!$session->enrollment()->whereHas('course', function($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })->exists()) {
            abort(403, 'Unauthorized access to this session.');
        }
        
        // Get enrollment IDs for this session
        $enrollmentIds = $session->enrollment()->pluck('id');
        
        // Get attendance records for this session grouped by date - FIXED QUERY
        $attendanceRecords = Attendance::whereIn('enrollment_id', $enrollmentIds)
            ->with('enrollment.trainee.user')
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date');
            
        // Get session enrollments for reference
        $enrollments = $session->enrollment()
            ->with('trainee.user')
            ->get();
            
        return view('trainer.attendance.session', compact('session', 'attendanceRecords', 'enrollments'));
    }

    /**
     * Show form to mark a date range as custom leave
     */
    public function createLeaveDay(EnrollmentSession $session, Request $request)
    {
        $trainer = Auth::user()->trainer->first();
        
        // Verify the trainer has access to this session
        if (!$session->enrollment()->whereHas('course', function($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })->exists()) {
            abort(403, 'Unauthorized access to this session.');
        }
        
        $date_from = $request->input('date_from', today()->format('Y-m-d'));
        $date_to = $request->input('date_to', today()->format('Y-m-d'));
        
        return view('trainer.attendance.create-leave-day', compact('session', 'date_from', 'date_to'));
    }
    
    /**
     * Store custom leave day range
     */
    public function storeLeaveDay(Request $request, EnrollmentSession $session)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        
        $trainer = Auth::user()->trainer->first();
        
        // Verify the trainer has access to this session
        if (!$session->enrollment()->whereHas('course', function($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })->exists()) {
            abort(403, 'Unauthorized access to this session.');
        }
        
        // Check for overlapping leave periods
        $overlappingLeave = LeaveDay::where('session_id', $session->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('date_from', [$request->date_from, $request->date_to])
                      ->orWhereBetween('date_to', [$request->date_from, $request->date_to])
                      ->orWhere(function($query) use ($request) {
                          $query->where('date_from', '<=', $request->date_from)
                                ->where('date_to', '>=', $request->date_to);
                      });
            })
            ->first();
            
        if ($overlappingLeave) {
            return redirect()->back()
                ->with('error', 'This date range overlaps with an existing leave period: ' . 
                      $overlappingLeave->date_from->format('M j') . ' - ' . 
                      $overlappingLeave->date_to->format('M j') . ' (' . $overlappingLeave->reason . ')')
                ->withInput();
        }
        
        // Create leave day
        LeaveDay::create([
            'session_id' => $session->id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'reason' => $request->reason,
            'description' => $request->description,
            'created_by' => Auth::id()
        ]);
        
        return redirect()->route('trainer.attendance.monthly', $session)
            ->with('success', 'Leave period marked successfully.');
    }
    
    /**
     * Remove custom leave day
     */
    public function deleteLeaveDay(EnrollmentSession $session, LeaveDay $leaveDay)
    {
        $trainer = Auth::user()->trainer->first();
        
        // Verify the trainer has access to this session and leave day belongs to session
        if ($leaveDay->session_id != $session->id || 
            !$session->enrollment()->whereHas('course', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })->exists()) {
            abort(403, 'Unauthorized action.');
        }
        
        $leaveDay->delete();
        
        return redirect()->route('trainer.attendance.monthly', $session)
            ->with('success', 'Leave period removed successfully.');
    }
    
    /**
     * Show monthly attendance view (updated to include leave days)
     */
    public function monthlyAttendance(EnrollmentSession $session, Request $request)
    {
        $trainer = Auth::user()->trainer->first();
        
        // Verify the trainer has access to this session
        if (!$session->enrollment()->whereHas('course', function($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })->exists()) {
            abort(403, 'Unauthorized access to this session.');
        }
        
        // Get the month and year from request or default to current month
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        // Create carbon instance for the selected month
        $selectedDate = Carbon::createFromDate($year, $month, 1);
        $startOfMonth = $selectedDate->copy()->startOfMonth();
        $endOfMonth = $selectedDate->copy()->endOfMonth();
        
        // Get all dates in the month
        $datesInMonth = collect();
        $currentDate = $startOfMonth->copy();
        while ($currentDate->lte($endOfMonth)) {
            $datesInMonth->push($currentDate->copy());
            $currentDate->addDay();
        }
        
        // Get all enrollments for this session with trainee details
        $enrollments = $session->enrollment()
            ->with('trainee.user', 'course') // Add course relationship        
            ->get();
        
        // Get enrollment IDs
        $enrollmentIds = $enrollments->pluck('id');
        
        // Get all attendance records for this session in the selected month
        $attendanceRecords = Attendance::whereIn('enrollment_id', $enrollmentIds)
            ->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->get()
            ->groupBy(['enrollment_id', function($item) {
                // Convert date string to Carbon instance for formatting
                return Carbon::parse($item->date)->format('Y-m-d');
            }]);
        
        // Get leave days for this session that overlap with the selected month
        $leaveDays = LeaveDay::where('session_id', $session->id)
            ->where(function($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date_from', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
                      ->orWhereBetween('date_to', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
                      ->orWhere(function($query) use ($startOfMonth, $endOfMonth) {
                          $query->where('date_from', '<=', $startOfMonth->format('Y-m-d'))
                                ->where('date_to', '>=', $endOfMonth->format('Y-m-d'));
                      });
            })
            ->get();
        
        // Create a map of dates that are leave days
        $leaveDates = collect();
        foreach ($leaveDays as $leaveDay) {
            $currentDate = Carbon::parse($leaveDay->date_from);
            $endDate = Carbon::parse($leaveDay->date_to);
            
            while ($currentDate->lte($endDate)) {
                if ($currentDate->between($startOfMonth, $endOfMonth)) {
                    $leaveDates->put($currentDate->format('Y-m-d'), $leaveDay);
                }
                $currentDate->addDay();
            }
        }
        
        // Get previous and next month for navigation
        $prevMonth = $selectedDate->copy()->subMonth();
        $nextMonth = $selectedDate->copy()->addMonth();
        
        
        return view('trainer.attendance.monthly', compact(
            'session', 
            'enrollments', 
            'datesInMonth', 
            'attendanceRecords',
            'leaveDays',
            'leaveDates',
            'selectedDate',
            'prevMonth',
            'nextMonth'
        ));
    }
}