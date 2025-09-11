<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminModuleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'assessment_package' => 'sometimes|file|mimes:pdf|max:10240',
        ]);

        $module = new Module([
            'name' => $validated['name'],
            'course_id' => $validated['course_id'],
        ]);

        if ($request->hasFile('assessment_package')) {
            $filePath = $request->file('assessment_package')->store('assessment-packages', 'public');
            $module->assesment_package_link = $filePath;
        }

        $module->save();

        return redirect()->route('admin.courses.show', $validated['course_id'])
            ->with('success', 'Module added successfully.');
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'assessment_package' => 'sometimes|file|mimes:pdf|max:10240',
        ]);

        $module->update([
            'name' => $validated['name'],
        ]);

        if ($request->hasFile('assessment_package')) {
            // Delete old file if exists
            if ($module->assesment_package_link) {
                Storage::disk('public')->delete($module->assesment_package_link);
            }
            
            $filePath = $request->file('assessment_package')->store('assessment-packages', 'public');
            $module->update(['assesment_package_link' => $filePath]);
        }

        return redirect()->route('admin.courses.show', $module->course_id)
            ->with('success', 'Module updated successfully.');
    }

    public function destroy(Module $module)
    {
        // Delete associated file if exists
        if ($module->assesment_package_link) {
            // Use the same disk that was used for storage
            Storage::disk('public')->delete($module->assesment_package_link);
        }

        $courseId = $module->course_id;
        $module->delete();

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Module deleted successfully.');
    }
}