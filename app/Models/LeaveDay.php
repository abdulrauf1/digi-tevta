<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LeaveDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'date_from',
        'date_to',
        'reason',
        'description',
        'created_by'
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function session()
    {
        return $this->belongsTo(EnrollmentSession::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all dates in this leave period
     */
    public function getDatesAttribute()
    {
        $dates = collect();
        $currentDate = Carbon::parse($this->date_from);
        $endDate = Carbon::parse($this->date_to);
        
        while ($currentDate->lte($endDate)) {
            $dates->push($currentDate->copy());
            $currentDate->addDay();
        }
        
        return $dates;
    }

    /**
     * Check if a specific date is within this leave period
     */
    public function includesDate($date)
    {
        $checkDate = Carbon::parse($date);
        return $checkDate->between($this->date_from, $this->date_to);
    }

    /**
     * Get the number of days in this leave period
     */
    public function getDayCountAttribute()
    {
        return Carbon::parse($this->date_from)->diffInDays($this->date_to) + 1;
    }
}