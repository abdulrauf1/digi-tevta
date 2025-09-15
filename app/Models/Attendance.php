<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'enrollment_id',
        'date',
        'status'
    ];

    protected $casts = [
        'date' => 'date', // This will automatically cast the date field to a Carbon instance
    ];


    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function session()
    {
        return $this->belongsTo(EnrollmentSession::class);
    }


}

