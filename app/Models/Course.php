<?php
// app/Models/Course.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Add this import

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'duration',
        'method',
        'field',
        'trainer_id' // Add trainer_id to fillable
    ];

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }
    
    public function sessions()
    {
        return $this->hasMany(EnrollmentSession::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function trainees(): BelongsToMany
    {
        return $this->belongsToMany(Trainee::class, 'course_trainee')
                    ->withTimestamps();
    }

    // Add relationship to Trainer
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }
}