<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'mollie_plan_id',
        'subscription_plan',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    // Relationships
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
