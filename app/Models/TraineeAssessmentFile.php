<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineeAssessmentFile extends Model
{
    protected $fillable = [
        'enrollment_id',
        'attendance_percentage',
        'module_id',
        'submission_date',
        'type',
        'status',
        'evidence_guide_link',
        'result',
        'comments'
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function evidence(): BelongsTo
    {
        return $this->belongsTo(Evidence::class);
    }

    public function modules(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
