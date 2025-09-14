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

Route::middleware(['auth', 'role:trainer'])->group(function () {
    Route::get('/trainer/dashboard', [TrainerDashboardController::class, 'index'])->name('trainer.dashboard');
    // Trainer routes
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
