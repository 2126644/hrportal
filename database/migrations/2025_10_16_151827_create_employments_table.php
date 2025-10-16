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
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('employment_type'); // full_time, part_time, intern, etc.
            $table->string('employment_status'); // active, probation, suspended, resigned, terminated
            $table->enum('company_branch', ['main', 'branch_1', 'branch_2', 'branch_3'])->default('main');
            $table->foreignId('report_to')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->date('date_joined')->nullable();
            $table->date('probation_start')->nullable();
            $table->date('probation_end')->nullable();
            $table->date('suspended_start')->nullable();
            $table->date('suspended_end')->nullable();
            $table->date('resigned_date')->nullable();
            $table->date('termination_date')->nullable();
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
