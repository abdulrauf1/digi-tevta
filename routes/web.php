<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminUserController,
    
};

use App\Http\Controllers\Admin_Clerk\{
    TrainerController,
    CourseController,
    TraineeController,   
    ModuleController,
    ReportController,
    EnrollmentController
};

use App\Http\Controllers\Clerk\{
    AdmissionDashboardController,
};

use App\Http\Controllers\Trainer\{
    TrainerDashboardController,
    TrainerCourseController,
    TrainerEnrollmentController,
    TrainerAttendanceController,
    TrainerAssessmentController
};

use App\Http\Controllers\Trainee\{
    TraineeDashboardController,
};


use Illuminate\Support\Facades\Route;

Route::get('/', function () {   
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// routes/web.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // Admin routes
});
Route::get('/storage/{filename}', [AdminFileController::class, 'show'])->name('storage.file');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');    

    // Users
    Route::resource('users', AdminUserController::class);
        
    
});


Route::prefix('admin-clerk')->name('admin-clerk.')->middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['role:admin|admission-clerk'])->group(function () {
        
        // Courses
        Route::resource('courses', CourseController::class);

        // Module routes
        Route::resource('modules', ModuleController::class)->except(['index', 'create', 'show']);

        
        // Trainers
        Route::resource('trainers', TrainerController::class);
        
        // Trainees
        Route::resource('trainees', TraineeController::class);
        
        // Routes for bulk import functionality
        
        Route::post('trainees/bulk-import', [TraineeController::class, 'bulkImport'])->name('trainees.bulk-import');        

        // Enrollments
        Route::resource('enrollments', EnrollmentController::class);
        
        // Enrollment Related
        Route::get('/get-sessions/{courseId}', [EnrollmentController::class, 'getSessions'])->name('get.sessions');
        Route::get('/check-enrollment', [EnrollmentController::class, 'checkEnrollment'])->name('check.enrollment');
        Route::post('/sessions', [EnrollmentController::class, 'storeSession'])->name('sessions.store');
        Route::get('/get-available-trainees/{sessionId}', [EnrollmentController::class, 'getAvailableTrainees']);
        
        Route::post('/enrollments/bulk-store', [EnrollmentController::class, 'bulkStore'])->name('enrollments.bulk-store');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});

Route::middleware(['auth', 'role:admission-clerk'])->group(function () {
    Route::get('/admission/dashboard', [AdmissionDashboardController::class, 'index'])->name('admission.dashboard');
    // Admission clerk routes
});

Route::prefix('/trainer')->name('trainer.')->middleware(['auth', 'role:trainer'])->group(function () {
    // Trainer routes
    
    Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');
    
    // Courses
    Route::get('/courses', [TrainerCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [TrainerCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/enrollments', [TrainerCourseController::class, 'enrollments'])->name('enrollments.course');
    
    // Enrollments
    Route::get('/enrollments/course/{course}', [TrainerEnrollmentController::class, 'courseEnrollments'])->name('enrollments.course');
    Route::get('/enrollments/session/{session}', [TrainerEnrollmentController::class, 'sessionEnrollments'])->name('enrollments.session');
    
    // Attendance
    Route::get('/attendance/create/{session}', [TrainerAttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/create/{session}', [TrainerAttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/session/{session}', [TrainerAttendanceController::class, 'sessionAttendance'])->name('attendance.session');
    Route::get('/attendance/monthly/{session}', [TrainerAttendanceController::class, 'monthlyAttendance'])->name('attendance.monthly');
    
    // Leave Days
    Route::get('/attendance/leave-day/{session}/create', [TrainerAttendanceController::class, 'createLeaveDay'])->name('attendance.create-leave-day');
    Route::post('/attendance/leave-day/{session}', [TrainerAttendanceController::class, 'storeLeaveDay'])->name('attendance.store-leave-day');
    Route::delete('/attendance/leave-day/{session}/{leaveDay}', [TrainerAttendanceController::class, 'deleteLeaveDay'])->name('attendance.delete-leave-day');

    // Assessments
    Route::get('/assessments', [TrainerAssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/assessments/{assessment}', [TrainerAssessmentController::class, 'show'])->name('assessments.show');
    Route::get('/assessments/create/{session}', [TrainerAssessmentController::class, 'create'])->name('assessments.create');
    Route::get('/assessments/session/{session}', [TrainerAssessmentController::class, 'sessionAssessments'])->name('assessments.session');
    Route::post('/assessments', [TrainerAssessmentController::class, 'store'])->name('assessments.store');

     

});

Route::middleware(['auth', 'role:trainee'])->group(function () {
    Route::get('/trainee/dashboard', [TraineeController::class, 'index'])->name('trainee.dashboard');
    // Trainee routes
});



Route::get('/trainees/download-template', function() {
    $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="trainees_template.csv"',
            'Access-Control-Allow-Origin' => '*', // For CORS
        ];

        $template = "name,email,cnic,gender,date_of_birth,contact,emergency_contact,domicile,education_level,address\n";
        $template .= "John Doe,john@example.com,42201-1234567-1,Male,1990-05-15,03001234567,03009876543,Islamabad,Bachelor,123 Main Street\n";

        return response()->make($template, 200, $headers);
})->name('trainees.download-template');


require __DIR__.'/auth.php';
