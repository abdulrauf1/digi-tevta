<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\EnrollmentSession;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerAssessmentController extends Controller
{
    /**
     * Display a listing of the assessments.
     */
    public function index()
    {
        $trainer = Auth::user()->trainer->first();
        
        $assessments = Assessment::whereHas('enrollment.session', function($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })
        ->with(['enrollment.trainee.user', 'enrollment.session', 'enrollment.course'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        return view('trainer.assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new assessment.
     */
    public function create(EnrollmentSession $session)
    {
        // Authorization - ensure trainer owns this session
        
        // dd($session,Auth::user()->trainer->first()->id);
        // if ($session->trainer_id !== Auth::user()->trainer->first()->id) {
        //     abort(403, 'Unauthorized action.');
        // }
        
        $enrollments = Enrollment::where('session_id', $session->id)
            ->with('trainee.user')
            ->get();
            
        return view('trainer.assessments.create', compact('session', 'enrollments'));
    }

    /**
     * Store a newly created assessment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_score' => 'required|numeric|min:0',
            'score' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
            'assessment_date' => 'required|date',
        ]);
        
        // Verify the enrollment belongs to a session owned by the trainer
        $enrollment = Enrollment::findOrFail($validated['enrollment_id']);
        if ($enrollment->session->trainer_id !== Auth::user()->trainer->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $assessment = Assessment::create($validated);
        
        return redirect()->route('trainer.assessments.session', $enrollment->session)
            ->with('success', 'Assessment created successfully.');
    }

    /**
     * Display the specified assessment.
     */
    public function show(Assessment $assessment)
    {
        // Authorization - ensure trainer owns this assessment
        if ($assessment->enrollment->session->trainer_id !== Auth::user()->trainer->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $assessment->load(['enrollment.trainee.user', 'enrollment.session', 'enrollment.course']);
        
        return view('trainer.assessments.show', compact('assessment'));
    }

    /**
     * Display assessments for a specific session.
     */
    public function sessionAssessments(EnrollmentSession $session)
    {
        // Authorization - ensure trainer owns this session
        if ($session->trainer_id !== Auth::user()->trainer->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $assessments = Assessment::whereHas('enrollment', function($query) use ($session) {
            $query->where('session_id', $session->id);
        })
        ->with(['enrollment.trainee.user'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        return view('trainer.assessments.session', compact('session', 'assessments'));
    }
}