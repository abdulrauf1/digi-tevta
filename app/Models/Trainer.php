<?php
// app/Models/Trainer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Add this if you have user relationship

class Trainer extends Model
{
    protected $fillable = [
        'name',
        'cnic',
        'gender',
        'contact',
        'user_id', // Add this
        'specialization',
        'experience_years',
        'qualification',
        'phone',
        'address'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function dailyLessonPlans(): HasMany
    {
        return $this->hasMany(DailyLessonPlan::class);
    }

    // Add relationship to Course
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}