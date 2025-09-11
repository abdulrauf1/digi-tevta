<?php
// app/Models/Trainee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Add this if you have user relationship

class Trainee extends Model
{
    protected $fillable = [
        'user_id', // Make sure this is included if you have it
        'cnic',
        'gender',
        'date_of_birth',
        'contact',
        'emergency_contact',
        'education_level',
        'domicile',
        'address'            
    ];

    // Add this if you have user relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'trainee_id', 'course_id')
                    ->withPivot('session', 'status') // Include enrollment fields
                    ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }   
    
}