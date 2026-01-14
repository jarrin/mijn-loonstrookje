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
        Schema::create('company_admin_office', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('admin_office_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['active', 'pending', 'inactive'])->default('pending');
            $table->timestamps();
            
            // Prevent duplicate entries
            $table->unique(['company_id', 'admin_office_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_admin_office');
    }
};
