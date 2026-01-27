<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'mollie_invoice_id',
        'mollie_payment_id',
        'subscription_id',
        'custom_subscription_id',
        'invoice_number',
        'amount',
        'description',
        'status',
        'issued_date',
        'due_date',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'issued_date' => 'date',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function customSubscription()
    {
        return $this->belongsTo(CustomSubscription::class);
    }

    // Helper methods
    public static function generateInvoiceNumber()
    {
        $year = now()->year;
        $lastInvoice = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -4) + 1 : 1;
        
        return 'INV-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function markAsCancelled()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($invoice) {
            $company = $invoice->company;
            if ($company) {
                $employer = $company->users()->where('role', 'employer')->first();
                if ($employer) {
                    \Mail::to($employer->email)->send(new \App\Mail\InvoiceCreated($invoice));
                }
            }
        });
    }
}
