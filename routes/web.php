<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdmissionController;


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
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Admin routes
});

Route::middleware(['auth', 'role:admission-clerk'])->group(function () {
    Route::get('/admission/dashboard', [AdmissionController::class, 'index'])->name('admission.dashboard');
    // Admission clerk routes
});

Route::middleware(['auth', 'role:trainer'])->group(function () {
    Route::get('/trainer/dashboard', [TrainerController::class, 'index']);
    // Trainer routes
});

Route::middleware(['auth', 'role:trainee'])->group(function () {
    Route::get('/trainee/dashboard', [TraineeController::class, 'index']);
    // Trainee routes
});




require __DIR__.'/auth.php';
