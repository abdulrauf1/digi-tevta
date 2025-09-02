<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'name',
        'competency_standard',
        'course_id'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function assessmentPackages(): HasMany
    {
        return $this->hasMany(AssessmentPackage::class);
    }

    public function dailyLessonPlans(): HasMany
    {
        return $this->hasMany(DailyLessonPlan::class);
    }
}
