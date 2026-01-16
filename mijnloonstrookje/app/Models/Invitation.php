<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'employer_id',
        'company_id',
        'invited_by',
        'role',
        'invitation_type',
        'status',
        'expires_at',
        'custom_subscription_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customSubscription()
    {
        return $this->belongsTo(CustomSubscription::class);
    }

    // Generate a secure token
    public static function generateToken()
    {
        return Str::random(64);
    }

    // Check if invitation is expired
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    // Check if invitation is still valid
    public function isValid()
    {
        return $this->status === 'pending' && !$this->isExpired();
    }
}
