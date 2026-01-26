<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'company_id',
        'uploader_id',
        'type',
        'file_path',
        'original_filename',
        'file_size',
        'version',
        'parent_document_id',
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
            'version' => 'decimal:1',
            'file_size' => 'integer',
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

    public function parentDocument()
    {
        return $this->belongsTo(Document::class, 'parent_document_id');
    }

    public function revisions()
    {
        return $this->hasMany(Document::class, 'parent_document_id')->orderBy('version', 'desc');
    }

    public function allRevisions()
    {
        // Get all documents in the revision chain
        $parent = $this->parent_document_id ? $this->parentDocument : $this;
        return Document::where('parent_document_id', $parent->id)
                      ->orWhere('id', $parent->id)
                      ->orderBy('version', 'desc')
                      ->get();
    }

    /**
     * Get the decrypted file contents
     */
    public function getDecryptedContent()
    {
        if (!Storage::disk('local')->exists($this->file_path)) {
            return null;
        }

        $encryptedContent = Storage::disk('local')->get($this->file_path);
        return Crypt::decrypt($encryptedContent);
    }

    /**
     * Store encrypted file
     */
    public static function storeEncrypted($file, $path)
    {
        $contents = file_get_contents($file->getRealPath());
        $encryptedContents = Crypt::encrypt($contents);
        
        Storage::disk('local')->put($path, $encryptedContents);
        
        return $path;
    }

    /**
     * Get file display name
     */
    public function getDisplayNameAttribute()
    {
        $typeName = match($this->type) {
            'payslip' => 'Loonstrook',
            'annual_statement' => 'Jaaroverzicht',
            'other' => 'Overig',
            default => ucfirst($this->type),
        };
        
        $period = '';
        if ($this->month) {
            $months = ['', 'Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 
                      'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
            $period = $months[$this->month] . ' ' . $this->year;
        } elseif ($this->week) {
            $period = 'Week ' . $this->week . ' ' . $this->year;
        } else {
            $period = $this->year;
        }
        
        return $typeName . ' - ' . $period;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute()
    {
        if (!$this->file_size) {
            return 'N/A';
        }
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }
}
