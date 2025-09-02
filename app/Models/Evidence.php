<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evidence extends Model
{
    protected $fillable = [
        'name',
        'assessment_package_id',
        'type',
        'link'
    ];

    public function assessmentPackage(): BelongsTo
    {
        return $this->belongsTo(AssessmentPackage::class);
    }

    public function traineeAssessmentFiles(): HasMany
    {
        return $this->hasMany(TraineeAssessmentFile::class);
    }
}