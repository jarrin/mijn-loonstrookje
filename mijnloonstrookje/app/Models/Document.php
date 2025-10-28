<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'company_id',
        'uploader_id',
        'type',
        'file_path',
        'version',
        'year',
        'month',
        'week',
        'period_type',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'is_deleted' => 'boolean',
            'deleted_at' => 'datetime',
            'year' => 'integer',
            'month' => 'integer',
            'week' => 'integer',
            'version' => 'integer',
        ];
    }

    protected $dates = ['deleted_at'];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
