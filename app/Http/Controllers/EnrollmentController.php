<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\EnrollmentSession;
use App\Models\Course;
use App\Models\Trainee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['course', 'enrollmentSession', 'trainee.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin-clerk.enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $courses = Course::where('status', 'active')->get();
        $trainees = Trainee::with('user')->get();
        $sessions = collect();

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
        $enrollment->load(['course', 'session', 'trainee.user']);
        return view('enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment)
    {
        $courses = Course::where('status', 'active')->get();
        $trainees = Trainee::with('user')->get();
        $sessions = EnrollmentSession::where('course_id', $enrollment->course_id)->get();

        return view('enrollments.edit', compact('enrollment', 'courses', 'trainees', 'sessions'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'session_id' => 'required|exists:enrollment_sessions,id',
            'status' => 'required|in:pending,confirm,cancel,altered'
        ]);

        // Check if trainee is already enrolled in this session (excluding current enrollment)
        $existingEnrollment = Enrollment::where('trainee_id', $enrollment->trainee_id)
            ->where('session_id', $validated['session_id'])
            ->where('id', '!=', $enrollment->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['session_id' => 'This trainee is already enrolled in this session.']);
        }

        try {
            DB::beginTransaction();

            $enrollment->update($validated);

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
            $enrollment->delete();
            return redirect()->route('enrollments.index')
                ->with('success', 'Enrollment deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('enrollments.index')
                ->with('error', 'Failed to delete enrollment: ' . $e->getMessage());
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
}