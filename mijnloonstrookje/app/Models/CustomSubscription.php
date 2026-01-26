<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'billing_period',
        'max_users',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'max_users' => 'integer',
        ];
    }

    // Relationships
    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
