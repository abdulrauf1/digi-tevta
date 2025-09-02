<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Enrollment extends Model
{
    protected $fillable = [
        'course_id',
        'session',
        'trainee_id',
        'trainer_id'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function traineeAssessmentFiles(): HasMany
    {
        return $this->hasMany(TraineeAssessmentFile::class);
    }

    public function dailyLessonPlans(): BelongsToMany
    {
        return $this->belongsToMany(DailyLessonPlan::class, 'daily_lesson_plan_enrollment')
                    ->withPivot('attendance_status', 'notes')
                    ->withTimestamps();
    }
}