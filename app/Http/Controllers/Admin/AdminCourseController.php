<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Trainer;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['trainer', 'trainer.user', 'modules'])->paginate(10);
        
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $trainers = Trainer::all()->load('user');
        
        return view('admin.courses.create', compact('trainers'));
    }

    public function store(Request $request)
    {
        // Validate course data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',            
            'method' => 'required|in:CBT,Traditional',
            'field' => 'required|string|max:255',
            'trainer_id' => 'required|exists:trainers,id',            
        ]);

        // Create the course
        $course = Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration' => $validated['duration'],            
            'method' => $validated['method'],
            'field' => $validated['field'],
            'trainer_id' => $validated['trainer_id'],
        ]);

        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load('trainer', 'trainer.user', 'modules');
        
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $trainers = Trainer::all();
        $course->load('modules');
        return view('admin.courses.edit', compact('course', 'trainers'));
    }

    public function update(Request $request, Course $course)
    {
        // Validate course data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',            
            'method' => 'required|in:CBT,Traditional',
            'field' => 'required|string|max:255',
            'trainer_id' => 'required|exists:trainers,id',
        ]);

        // Update the course
        $course->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration' => $validated['duration'],            
            'method' => $validated['method'],
            'field' => $validated['field'],
            'trainer_id' => $validated['trainer_id'],
        ]);

        
        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        // Delete associated files before deleting the course
        foreach ($course->modules as $module) {
            if ($module->competency_standard_file_link) {
                Storage::disk('public')->delete($module->competency_standard_file_link);
            }
            if ($module->assesment_package_link) {
                Storage::disk('public')->delete($module->assesment_package_link);
            }
        }
        
        $course->delete();
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}