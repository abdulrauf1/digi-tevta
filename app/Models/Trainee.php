<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Add this import

class Trainee extends Model
{
    protected $fillable = [
        'cnic',
        'gender',
        'date_of_birth',
        'contact',
        'emergency_contact',
        'education_level',
        'domicile',
        'address'            
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_trainee')
                    ->withTimestamps();
    }

}