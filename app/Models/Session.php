<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    protected $table = 'sessions';
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id', 'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'
    ];
    
    protected $casts = [
        'last_activity' => 'datetime',
    ];
    
    /**
     * Get the user that owns the session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the login time attribute.
     */
    public function getLoginTimeAttribute()
    {
        return $this->last_activity;
    }
}