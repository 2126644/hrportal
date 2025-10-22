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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');  // add foreign key column
            $table->string('name');
            $table->date('applied_date');
            $table->enum('leave_type', ['annual_leave', 'medical_leave', 'emergency_leave', 'hospitalization', 'maternity', 'compassionate', 'replacement', 'unpaid_leave', 'marriage']); 
            $table->enum('leave_length', ['full_day', 'AM', 'PM']);
            $table->text('reason')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days');
            $table->string('attachment')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('reject_reason')->nullable();
            $table->string('action')->nullable();

            $table->foreign('employee_id')->references('employee_id')->on('employees')->cascadeOnDelete();  // when the parent record is deleted, the child is deleted
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
