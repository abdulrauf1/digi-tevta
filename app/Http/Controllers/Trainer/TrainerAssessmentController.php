<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Module;
use App\Models\Enrollment;
use App\Models\EnrollmentSession;
use App\Models\TraineeAssessmentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TrainerAssessmentController extends Controller
{
    /**
     * Display a listing of the courses for the authenticated trainer.
     */
    public function index(EnrollmentSession $session, Request $request)
    {
        $trainer = Auth::user()->trainer->first();

        $course = Course::with(['modules', 'enrollment.trainee.user'])
                    ->where('trainer_id', $trainer->id)
                    ->firstOrFail();

        // Get assessments with filters
        $assessments = TraineeAssessmentFile::with([
                'enrollment.trainee.user', 
                'modules' // Using the relationship name as defined in your model
            ])
            ->whereHas('enrollment', function($query) use ($course, $session) {
                $query->where('course_id', $course->id)
                    ->where('session_id', $session->id);
            });

        // Apply filters if they exist
        if ($request->has('status') && $request->status != '') {
            $assessments->where('status', $request->status);
        }
        
        if ($request->has('result') && $request->result != '') {
            $assessments->where('result', $request->result);
        }
        
        if ($request->has('type') && $request->type != '') {
            $assessments->where('type', $request->type);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $assessments->where(function($query) use ($search) {
                $query->whereHas('enrollment.trainee.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('modules', function($q) use ($search) { // Using plural to match relationship name
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Handle export
        if ($request->has('export') && $request->export == 'true') {
            return $this->exportAssessments($assessments->get());
        }

        $assessments = $assessments->paginate(10);

        return view('trainer.assessments.index', compact('course', 'session', 'assessments'));
    }

    // Add this method to your controller for export functionality
    private function exportAssessments($assessments)
    {
        $fileName = 'assessments_' . date('Y-m-d') . '.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Trainee', 'Trainee CNIC', 'Module', 'Type', 'Status', 'Result', 'Submission Date'];

        $callback = function() use($assessments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($assessments as $assessment) {
                $row = [
                    $assessment->enrollment->trainee->user->name,
                    $assessment->enrollment->trainee->cnic,
                    $assessment->module->name ?? 'N/A',
                    ucfirst($assessment->type),
                    ucfirst($assessment->status),
                    ucfirst($assessment->result),
                    $assessment->submission_date ? \Carbon\Carbon::parse($assessment->submission_date)->format('M d, Y') : 'N/A'
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
 
    public function createEntries(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'enrollment_ids' => 'required|array',
            'enrollment_ids.*' => 'exists:enrollments,id',
            'type' => 'required|in:formative,integrated',
            'submission_date' => 'required|date|after_or_equal:today',
        ]);

        $module = Module::findOrFail($request->module_id);
        
        if (!$module->assesment_package_link) {
            return redirect()->back()->with('error', 'Assessment package not configured for this module. Please contact administrator.');
        }
        
        $filePath = $module->assesment_package_link;
        
        if (strpos($filePath, '/') === 0) {
            $filePath = substr($filePath, 1);
        }
        
        $fullPath = public_path($filePath);
        
        if (!file_exists($fullPath)) {
            $storagePath = storage_path('app/public/' . $filePath);
            
            if (!file_exists($storagePath)) {
                $basename = basename($filePath);
                $alternativePath = public_path('uploads/' . $basename);
                
                if (!file_exists($alternativePath)) {
                    return redirect()->back()->with('error', 'Assessment package file not found. Please contact administrator to upload the file. File: ' . $filePath);
                } else {
                    $sourcePath = $alternativePath;
                }
            } else {
                $sourcePath = $storagePath;
            }
        } else {
            $sourcePath = $fullPath;
        }

        $successCount = 0;
        $errorCount = 0;
        $skippedCount = 0;

        foreach ($request->enrollment_ids as $enrollmentId) {
            try {
                // Check if an entry already exists for this enrollment_id and module_id
                $existingEntry = TraineeAssessmentFile::where('enrollment_id', $enrollmentId)
                    ->where('module_id', $request->module_id)
                    ->first();

                if ($existingEntry) {
                    $skippedCount++;
                    continue;
                }

                $enrollment = Enrollment::with(['trainee.user', 'enrollmentSession', 'course.modules'])->findOrFail($enrollmentId);
                
                $sessionName = Str::slug($enrollment->enrollmentSession->name ?? 'nosession');
                $traineeName = Str::slug($enrollment->trainee->user->name);
                $moduleName = Str::slug($module->name);
                $fileName = "{$sessionName}_{$traineeName}_{$moduleName}.pdf";
                
                $directory = 'assessment_files/' . $enrollment->course->name . $enrollment->enrollmentSession->name . '/' . $module->name;
                $fullDirectoryPath = storage_path('app/public/' . $directory);
                
                if (!file_exists($fullDirectoryPath)) {
                    mkdir($fullDirectoryPath, 0777, true);
                }
                
                $destinationPath = storage_path("app/public/{$directory}/{$fileName}");
                
                if (file_exists($destinationPath)) {
                    $timestamp = time();
                    $fileName = "{$sessionName}_{$traineeName}_{$moduleName}_{$timestamp}.pdf";
                    $destinationPath = storage_path("app/public/{$directory}/{$fileName}");
                }
                
                if (copy($sourcePath, $destinationPath)) {
                    TraineeAssessmentFile::create([
                        'enrollment_id' => $enrollmentId,
                        'module_id' => $request->module_id,
                        'type' => $request->type,
                        'evidence_guide_link' => "storage/{$directory}/{$fileName}",
                        'attendance_percentage' => 0,
                        'submission_date' => $request->submission_date,
                        'status' => 'pending',
                        'result' => 'incomplete'
                    ]);
                    $successCount++;
                } else {
                    TraineeAssessmentFile::create([
                        'enrollment_id' => $enrollmentId,
                        'module_id' => $request->module_id,
                        'type' => $request->type,
                        'evidence_guide_link' => $module->assesment_package_link,
                        'attendance_percentage' => 0,
                        'submission_date' => $request->submission_date,
                        'status' => 'pending',
                        'result' => 'incomplete',
                        'comments' => 'Original assessment file used due to copy failure'
                    ]);
                    $errorCount++;
                }
            } catch (\Exception $e) {
                Log::error("Error creating assessment for enrollment {$enrollmentId}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $message = "Operation completed: {$successCount} created, {$skippedCount} skipped (duplicates), {$errorCount} errors.";
        
        if ($errorCount > 0) {
            return redirect()->back()->with('warning', $message);
        }

        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Show the form for creating a new assessment for a specific module.
     */
    public function create(Request $request, EnrollmentSession $session)
    {
        // Get the course ID from the query parameter
        $courseId = $request->query('course');

        $trainer = Auth::user()->trainer->first();

        $course = Course::with(['modules', 'enrollment.trainee.user'])
                        ->where('id', $courseId)
                        ->where('trainer_id', $trainer->id)
                        ->firstOrFail();

        
        $selectedCourse = $course;
        
        $enrollments = $course->enrollment;

        

        return view('trainer.assessments.create', compact('course','session','selectedCourse','enrollments'));
    }

    /**
     * Store a newly created assessment in storage.
     */
    public function store(Request $request, $courseId, $moduleId)
    {
        $request->validate([
            'type' => 'required|in:formative,integrated',
            'evidence_guide_link' => 'required|url',
        ]);
        
        $course = Course::with('enrollments')
                        ->where('id', $courseId)
                        ->where('trainer_id', Auth::id())
                        ->firstOrFail();
        
        // Create assessment entries for each enrolled trainee
        foreach ($course->enrollments as $enrollment) {
            // Skip if enrollment status is not confirmed
            if ($enrollment->status !== 'confirm') {
                continue;
            }
            
            TraineeAssessmentFile::create([
                'enrollment_id' => $enrollment->id,
                'attendance_percentage' => 0, // Default value, can be updated later
                'module_id' => $moduleId,
                'type' => $request->type,
                'status' => 'pending',
                'evidence_guide_link' => $request->evidence_guide_link,
                'result' => 'incomplete',
                'comments' => 'Assessment created by trainer',
            ]);
        }
        
        return redirect()->route('trainer.assessments.index')
                         ->with('success', 'Assessment created successfully for all trainees.');
    }

    /**
     * Display assessments for a specific module.
     */
    public function show($courseId, $moduleId)
    {
        $course = Course::with(['modules', 'enrollments.trainee'])
                        ->where('id', $courseId)
                        ->where('trainer_id', Auth::id())
                        ->firstOrFail();
        
        $module = $course->modules->find($moduleId);
        
        if (!$module) {
            return redirect()->back()->with('error', 'Module not found for this course.');
        }
        
        $assessments = TraineeAssessmentFile::with(['enrollment.trainee'])
                        ->where('module_id', $moduleId)
                        ->get();
        
        return view('trainer.assessments.show', compact('course', 'module', 'assessments'));
    }

    /**
     * Show the form for editing a specific trainee assessment.
     */
    public function edit($assessmentId)
    {
        $assessment = TraineeAssessmentFile::with(['enrollment.course', 'module'])
                        ->findOrFail($assessmentId);
        
        // Verify that the trainer owns this course
        if ($assessment->enrollment->course->trainer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('trainer.assessments.edit', compact('assessment'));
    }

    /**
     * Update the specified trainee assessment in storage.
     */
    public function update(Request $request, $assessmentId)
    {
        $request->validate([
            'attendance_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pending,completed,incomplete',
            'submission_date' => 'nullable|date',
            'result' => 'required|in:competent,not yet competent,incomplete',
            'comments' => 'nullable|string',
        ]);
        
        $assessment = TraineeAssessmentFile::with(['enrollment.course'])
                        ->findOrFail($assessmentId);
        
        // Verify that the trainer owns this course
        if ($assessment->enrollment->course->trainer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $assessment->update($request->all());
        
        return redirect()->route('trainer.assessments.show', [
                        'courseId' => $assessment->enrollment->course_id, 
                        'moduleId' => $assessment->module_id
                    ])
                    ->with('success', 'Assessment updated successfully.');
    }
}