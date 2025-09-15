<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentSession extends Model
{
    //

    protected $fillable = [
        'name',
        'description',
        'duration',
        'start_date',
        'end_date',
        'status'
    ];

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class, 'session_id');
    }   

    public function getOngoingSessions()
    {
        return self::where('status', 'ongoing')->get();
    }   

    public function gettOngoningSessionCourses()
    {
        return $this->enrollment()->with('course')->get()->pluck('course')->unique('id');
    }
}
