<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany; // Add this import
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function trainee(): HasMany
    {
        return $this->hasMany(Trainee::class);
    }

    public function trainer(): HasMany
    {
        return $this->hasMany(Trainer::class);
    }

    public function sessions()
    {
        return DB::table('sessions')
            ->where('user_id', $this->id)
            ->orderBy('last_activity', 'desc');
    }

    /**
     * Get the last login information
     */
    public function getLastLoginAttribute()
    {
        $session = $this->sessions()->first();
        
        if (!$session) {
            return null;
        }

        return [
            'ip_address' => $session->ip_address,
            'user_agent' => $session->user_agent,
            'last_activity' => Carbon::createFromTimestamp($session->last_activity),
            'device' => $this->getDeviceInfo($session->user_agent),
            'browser' => $this->getBrowserInfo($session->user_agent),
        ];
    }

    /**
     * Extract device information from user agent string
     */
    private function getDeviceInfo($userAgent)
    {
        if (preg_match('/iPhone|iPad|iPod/', $userAgent)) {
            return 'iOS Device';
        } elseif (preg_match('/Android/', $userAgent)) {
            return 'Android Device';
        } elseif (preg_match('/Windows/', $userAgent)) {
            return 'Windows PC';
        } elseif (preg_match('/Macintosh|Mac OS/', $userAgent)) {
            return 'Mac';
        } elseif (preg_match('/Linux/', $userAgent)) {
            return 'Linux PC';
        } else {
            return 'Unknown Device';
        }
    }

    /**
     * Extract browser information from user agent string
     */
    private function getBrowserInfo($userAgent)
    {
        if (preg_match('/Chrome/', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Firefox/', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/Safari/', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Edge/', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/Opera/', $userAgent)) {
            return 'Opera';
        } else {
            return 'Unknown Browser';
        }
    }
}
