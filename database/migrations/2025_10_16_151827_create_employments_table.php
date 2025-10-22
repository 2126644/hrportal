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
        Schema::create('employments', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');  // add foreign key column
            $table->string('employment_type'); // full_time, part_time, intern, etc.
            $table->string('employment_status'); // active, probation, suspended, resigned, terminated
            $table->enum('company_branch', ['AHG', 'D-8CEFC'])->default('AHG');
            $table->string('report_to')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->date('date_joined')->nullable();
            $table->date('probation_start')->nullable();
            $table->date('probation_end')->nullable();
            $table->date('suspended_start')->nullable();
            $table->date('suspended_end')->nullable();
            $table->date('resigned_date')->nullable();
            $table->date('termination_date')->nullable();

            $table->foreign('employee_id')->references('employee_id')->on('employees')->cascadeOnDelete();  
            $table->foreign('report_to')->references('employee_id')->on('employees')->nullOnDelete();

            $table->timestamps();

            // Indexes for better performance
            $table->index(['employment_status']);
            $table->index(['company_branch']);
            $table->index(['probation_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employments');
    }
};
