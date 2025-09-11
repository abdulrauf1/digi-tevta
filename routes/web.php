<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminUserController,
    AdminTrainerController,
    AdminCourseController,
    AdminTraineeController,   
    AdminModuleController 
};

use App\Http\Controllers\Clerk\{
    AdmissionController,
};

use App\Http\Controllers\Trainer\{
    TrainerController,
};

use App\Http\Controllers\Trainee\{
    TraineeController,
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
    
    // Courses
    Route::resource('courses', AdminCourseController::class);

    // Module routes
    Route::resource('modules', AdminModuleController::class)->except(['index', 'create', 'show']);

    
    // Trainers
    Route::resource('trainers', AdminTrainerController::class);
    
    // Trainees
    Route::resource('trainees', AdminTraineeController::class);
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

Route::middleware(['auth', 'role:admission-clerk'])->group(function () {
    Route::get('/admission/dashboard', [AdmissionController::class, 'index'])->name('admission.dashboard');
    // Admission clerk routes
});

Route::middleware(['auth', 'role:trainer'])->group(function () {
    Route::get('/trainer/dashboard', [TrainerController::class, 'index'])->name('trainer.dashboard');
    // Trainer routes
});

Route::middleware(['auth', 'role:trainee'])->group(function () {
    Route::get('/trainee/dashboard', [TraineeController::class, 'index'])->name('trainee.dashboard');
    // Trainee routes
});




require __DIR__.'/auth.php';
