<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainer extends Model
{
    protected $fillable = [
        'name',
        'cnic',
        'gender',
        'contact'
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function dailyLessonPlans(): HasMany
    {
        return $this->hasMany(DailyLessonPlan::class);
    }
}