<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password' => 'hashed',
            'is_deleted' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    protected $dates = ['deleted_at'];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function uploadedDocuments()
    {
        return $this->hasMany(Document::class, 'uploader_id');
    }

    public function employeeDocuments()
    {
        return $this->hasMany(Document::class, 'employee_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}