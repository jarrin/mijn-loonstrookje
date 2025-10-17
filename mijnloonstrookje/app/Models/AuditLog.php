<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'action',
        'target_type',
        'target_id',
    ];

    protected function casts(): array
    {
        return [
            'target_id' => 'integer',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function target()
    {
        return $this->morphTo();
    }
}
