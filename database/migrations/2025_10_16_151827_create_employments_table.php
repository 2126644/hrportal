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
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('employment_type'); // full time, part time, intern, etc.
            $table->string('employment_status'); // active, probation, suspended, resigned, terminated
            $table->enum('company_branch', ['AHG', 'D-8CEFC'])->default('AHG');
            $table->string('report_to')->nullable();
            $table->string('position')->nullable();
            $table->date('date_of_employment')->nullable();
            $table->date('probation_start')->nullable();
            $table->date('probation_end')->nullable();
            $table->date('suspension_start')->nullable();
            $table->date('suspension_end')->nullable();
            $table->date('resignation_date')->nullable();
            $table->date('last_working_day')->nullable();
            $table->date('termination_date')->nullable();
            $table->time('work_start_time')->nullable();
            $table->time('work_end_time')->nullable();

            $table->foreign('employee_id')->references('employee_id')->on('employees')->cascadeOnDelete();
            $table->foreign('report_to')->references('employee_id')->on('employees')->nullOnDelete();

            $table->timestamps();
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
