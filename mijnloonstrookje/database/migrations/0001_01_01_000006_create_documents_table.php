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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploader_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['payslip', 'annual_statement', 'other']);
            $table->string('file_path'); // Encrypted file path
            $table->string('original_filename'); // Original file name
            $table->integer('file_size')->nullable(); // File size in bytes
            $table->decimal('version', 3, 1)->default(1.0);
            $table->integer('year');
            $table->integer('month')->nullable();
            $table->integer('week')->nullable();
            $table->enum('period_type', ['Maandelijks', 'Weekelijks', '2-wekelijks', 'Jaarlijks']);
            $table->text('note')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
