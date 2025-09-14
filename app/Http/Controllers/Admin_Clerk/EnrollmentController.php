<?php

namespace App\Http\Controllers\Admin_Clerk;
use App\Http\Controllers\Controller;

use App\Models\Enrollment;
use App\Models\EnrollmentSession;
use App\Models\Course;
use App\Models\Trainee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters from request
        $search = $request->input('search');
        $status = $request->input('status');
        $courseId = $request->input('course');
        
        // Start building the query
        $query = Enrollment::with(['course', 'enrollmentSession', 'trainee.user'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters if they exist
        if ($search) {
            $query->whereHas('trainee.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('course', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        
        $enrollments = $query->paginate(10);
        
        // Get counts for statistics
        $confirmedCount = Enrollment::where('status', 'confirm')->count();
        $pendingCount = Enrollment::where('status', 'pending')->count();
        $cancelledCount = Enrollment::where('status', 'cancel')->count();
        
        // Get active courses for filter dropdown
        $courses = Course::where('status', 'active')->get();
        
        return view('admin-clerk.enrollments.index', compact(
            'enrollments', 
            'courses',
            'confirmedCount',
            'pendingCount',
            'cancelledCount'
        ));
    }

    public function create()
    {
        $courses = Course::where('status', 'active')->get();
        $trainees = Trainee::with('user')->get();
        $sessions = EnrollmentSession::get();

        // dd($sessions);
        
        return view('admin-clerk.enrollments.create', compact('courses', 'trainees', 'sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trainee_id' => 'required|exists:trainees,id',
            'course_id' => 'required|exists:courses,id',
            'session_id' => 'required|exists:enrollment_sessions,id',
            'status' => 'required|in:pending,confirm,cancel,altered'
        ]);

        // Check if trainee is already enrolled in this session
        $existingEnrollment = Enrollment::where('trainee_id', $validated['trainee_id'])
            ->where('session_id', $validated['session_id'])
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['trainee_id' => 'This trainee is already enrolled in this session.']);
        }

        try {
            DB::beginTransaction();

            $enrollment = Enrollment::create($validated);

            DB::commit();

            return redirect()->route('enrollments.index')
                ->with('success', 'Enrollment created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create enrollment: ' . $e->getMessage()]);
        }
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['course', 'enrollmentSession', 'trainee.user']);
        return view('admin-clerk.enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment)
    {
        $courses = Course::where('status', 'active')->get();
        $trainees = Trainee::with('user')->get();
        $sessions = Enrollment::with('enrollmentSession')->where('course_id', $enrollment->course_id)->get();
        
        return view('admin-clerk.enrollments.edit', compact('enrollment', 'courses', 'trainees', 'sessions'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'session_id' => 'required|exists:enrollment_sessions,id',            
        ]);

        

        // Check if trainee is already enrolled in this session (excluding current enrollment)
        $existingEnrollment = Enrollment::where('trainee_id', $enrollment->trainee_id)
            ->where('session_id', $validated['course_id'])
            ->where('course_id',$validated['session_id'])
            ->where('id', '!=', $enrollment->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['session_id' => 'This trainee is already enrolled in this session.']);
        }

        try {
            DB::beginTransaction();

            $enrollment->update([
                'course_id' => $validated['course_id'],
                'session_id' => $validated['session_id'],
                'status' => 'altered',
            ]);

            DB::commit();

            return redirect()->route('enrollments.index')
                ->with('success', 'Enrollment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update enrollment: ' . $e->getMessage()]);
        }
    }

    public function destroy(Enrollment $enrollment)
    {
        try {
            $enrollment->update(['status' => 'cancel']);            
            return redirect()->route('enrollments.index')
                ->with('success', 'Enrollment Cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->route('enrollments.index')
                ->with('error', 'Failed to Cancel enrollment: ' . $e->getMessage());
        }
    }

    // AJAX methods
    public function getSessions($courseId)
    {
        $sessions = EnrollmentSession::where('course_id', $courseId)
            ->where('status', 'upcoming')
            ->get();

        return response()->json($sessions);
    }

    public function checkEnrollment(Request $request)
    {
        $request->validate([
            'trainee_id' => 'required|exists:trainees,id',
            'session_id' => 'required|exists:enrollment_sessions,id'
        ]);

        $existingEnrollment = Enrollment::where('trainee_id', $request->trainee_id)
            ->where('session_id', $request->session_id)
            ->first();

        return response()->json([
            'exists' => $existingEnrollment !== null,
            'enrollment' => $existingEnrollment
        ]);
    }
    
    // Store new session (for the modal form)
    public function storeSession(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',            
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'session_description' => 'nullable|string|max:255',
            'duration' => 'required'
        ]);
        
        try {
            DB::beginTransaction();
            
            $session = EnrollmentSession::create([
                'name' => $validated['name'],
                'description' => $validated['session_description'],
                'duration' => $validated['duration'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => 'upcoming'
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Session created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create session: ' . $e->getMessage()]);
        }
    }

    // Get available trainees for a session
    public function getAvailableTrainees($sessionId)
    {
        try {
            // Get trainees not enrolled in this session
            $enrolledTraineeIds = Enrollment::where('session_id', $sessionId)
                ->pluck('trainee_id')
                ->toArray();
            
            $availableTrainees = Trainee::with('user')
                ->whereNotIn('id', $enrolledTraineeIds)
                ->get();
            
            return response()->json($availableTrainees);
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }

    // Bulk store enrollments
    public function bulkStore(Request $request)
    {
        
        $validated = $request->validate([
            'course_ids' => 'required|array',
            'course_ids.*' => 'required|exists:courses,id',
            'session_id' => 'required|exists:enrollment_sessions,id',
            'status' => 'required|in:pending,confirm,cancel,altered',
            'trainee_ids' => 'required|array',
            'trainee_ids.*' => 'exists:trainees,id'
        ]);

        try {
            DB::beginTransaction();
            
            $enrollments = [];
            $enrollmentCount = 0;
            
            // Loop through each trainee ID
            foreach ($validated['trainee_ids'] as $index => $traineeId) {
                // Get the corresponding course ID for this trainee
                $courseId = $validated['course_ids'][$index] ?? null;
                
                if (!$courseId) {
                    continue; // Skip if no course ID is provided for this trainee
                }
                
                // Check if trainee is already enrolled in this session and course
                $existing = Enrollment::where('trainee_id', $traineeId)
                    ->where('session_id', $validated['session_id'])
                    ->where('course_id', $courseId)
                    ->exists();
                    
                if (!$existing) {
                    $enrollments[] = [
                        'trainee_id' => $traineeId,
                        'course_id' => $courseId,
                        'session_id' => $validated['session_id'],
                        'status' => $validated['status'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $enrollmentCount++;
                }
            }
            
            if (!empty($enrollments)) {
                Enrollment::insert($enrollments);
            }
            
            DB::commit();
            
            return redirect()->route('admin-clerk.enrollments.index')
                ->with('success', $enrollmentCount . ' enrollments created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create enrollments: ' . $e->getMessage()]);
        }
    }
}