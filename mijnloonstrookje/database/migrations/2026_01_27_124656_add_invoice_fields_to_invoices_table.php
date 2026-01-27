<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Mollie payment reference
            $table->string('mollie_payment_id')->nullable()->after('mollie_invoice_id');
            
            // Subscription references
            $table->foreignId('subscription_id')->nullable()->after('company_id')->constrained('subscriptions')->onDelete('set null');
            $table->foreignId('custom_subscription_id')->nullable()->after('subscription_id')->constrained('custom_subscriptions')->onDelete('set null');
            
            // Invoice metadata
            $table->string('invoice_number')->unique()->after('id');
            $table->text('description')->nullable()->after('amount');
            $table->date('issued_date')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['subscription_id']);
            $table->dropForeign(['custom_subscription_id']);
            
            // Drop columns
            $table->dropColumn([
                'mollie_payment_id',
                'subscription_id',
                'custom_subscription_id',
                'invoice_number',
                'description',
                'issued_date'
            ]);
        });
    }
};
