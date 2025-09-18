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
    public function index(Request $request, EnrollmentSession $session)
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

        return view('trainer.assessments.index', compact('course','session','selectedCourse','enrollments'));
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
        
        // Check if the module has an assessment package
        if (!$module->assesment_package_link) {
            return redirect()->back()->with('error', 'Assessment package not configured for this module. Please contact administrator.');
        }
        
        // Check if file exists - handle different path formats
        $filePath = $module->assesment_package_link;
        
        // Remove leading slash if present to ensure consistent path handling
        if (strpos($filePath, '/') === 0) {
            $filePath = substr($filePath, 1);
        }
        
        // Check if file exists in public path
        $fullPath = public_path($filePath);
        
        if (!file_exists($fullPath)) {
            // Try alternative path - maybe the file is stored with storage_path instead
            $storagePath = storage_path('app/public/' . $filePath);
            
            if (!file_exists($storagePath)) {
                // Try one more approach - check if it's a URL or just filename
                $basename = basename($filePath);
                $alternativePath = public_path('uploads/' . $basename);
                
                if (!file_exists($alternativePath)) {
                    return redirect()->back()->with('error', 'Assessment package file not found. Please contact administrator to upload the file. File: ' . $filePath);
                } else {
                    $sourcePath = $alternativePath;
                }
            } else {
                // Use storage path
                $sourcePath = $storagePath;
            }
        } else {
            // Use public path
            $sourcePath = $fullPath;
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($request->enrollment_ids as $enrollmentId) {
            try {
                $enrollment = Enrollment::with(['trainee.user', 'enrollmentSession', 'course.modules'])->findOrFail($enrollmentId);
                
                // Generate file name: sessionname_traineename_modulename.pdf
                $sessionName = Str::slug($enrollment->enrollmentSession->name ?? 'nosession');
                $traineeName = Str::slug($enrollment->trainee->user->name);
                $moduleName = Str::slug($module->name);
                $fileName = "{$sessionName}_{$traineeName}_{$moduleName}.pdf";
                

                // Create directory in storage if it doesn't exist
                $directory = 'assessment_files/' . $enrollment->course->name . $enrollment->enrollmentSession->name . '/' . $module->name;
                $fullDirectoryPath = storage_path('app/public/' . $directory);
                
                if (!file_exists($fullDirectoryPath)) {
                    mkdir($fullDirectoryPath, 0777, true);
                }
                
                // Copy the PDF file to storage
                $destinationPath = storage_path("app/public/{$directory}/{$fileName}");
                
                // Check if file already exists to avoid overwriting
                if (file_exists($destinationPath)) {
                    // Add timestamp to make filename unique
                    $timestamp = time();
                    $fileName = "{$sessionName}_{$traineeName}_{$moduleName}_{$timestamp}.pdf";
                    $destinationPath = storage_path("app/public/{$directory}/{$fileName}");
                }
                
                if (copy($sourcePath, $destinationPath)) {
                    // Create assessment entry with storage path
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
                    // If we can't copy, use the original file path
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

        if ($errorCount > 0) {
            return redirect()->back()->with('warning', "Assessment entries created with {$successCount} successes and {$errorCount} errors. Some files may not have been copied correctly.");
        }

        return redirect()->back()->with('success', "{$successCount} assessment entries created successfully.");
    }
    
    /**
     * Show the form for creating a new assessment for a specific module.
     */
    public function create(Request $request, EnrollmentSession $session)
    {
        // Get the course ID from the query parameter
        $courseId = $request->query('course');

        $trainer = Auth::user()->trainer->first();


        $course = Course::with(['modules', 'enrollment.trainee'])
                        ->where('id', $courseId)
                        ->where('trainer_id', $trainer->id)
                        ->firstOrFail();
        
        
        
        return view('trainer.assessments.create', compact('course'));
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