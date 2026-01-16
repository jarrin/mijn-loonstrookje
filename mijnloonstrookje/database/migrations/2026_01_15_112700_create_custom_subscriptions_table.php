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
        Schema::create('custom_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 8, 2);
            $table->string('billing_period'); // 'maandelijks' or 'jaarlijks'
            $table->integer('max_users');
            $table->timestamps();
        });

        // Add custom_subscription_id to companies table
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('custom_subscription_id')->nullable()->constrained('custom_subscriptions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['custom_subscription_id']);
            $table->dropColumn('custom_subscription_id');
        });

        Schema::dropIfExists('custom_subscriptions');
    }
};
