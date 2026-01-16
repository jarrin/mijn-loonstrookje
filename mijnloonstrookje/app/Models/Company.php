<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'kvk_number',
        'logo_path',
        'primary_color',
        'subscription_id',
    ];

    protected function casts(): array
    {
        return [
            'is_deleted' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    protected $dates = ['deleted_at'];

    // Relationships
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function adminOffices()
    {
        return $this->belongsToMany(User::class, 'company_admin_office', 'company_id', 'admin_office_id')
                    ->where('role', 'administration_office')
                    ->withPivot('status')
                    ->withTimestamps();
    }
    
    public function activeAdminOffices()
    {
        return $this->belongsToMany(User::class, 'company_admin_office', 'company_id', 'admin_office_id')
                    ->wherePivot('status', 'active')
                    ->where('role', 'administration_office')
                    ->withPivot('status')
                    ->withTimestamps();
    }
    
    /**
     * Get secondary color (60% opacity of primary color)
     */
    public function getSecondaryColorAttribute()
    {
        if (!$this->primary_color) {
            return 'rgba(59, 130, 246, 0.6)'; // Default blue with 60% opacity
        }
        
        // Convert hex to RGB
        $hex = str_replace('#', '', $this->primary_color);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return "rgba($r, $g, $b, 0.6)";
    }
}
