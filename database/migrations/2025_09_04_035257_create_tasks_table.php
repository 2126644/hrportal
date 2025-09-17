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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->nullable();  // add foreign key column
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('assigned_to')->nullable();
            $table->string('assigned_by')->nullable();
            $table->enum('status', ['to-do', 'in-progress', 'in-review','completed'])->default('to-do');
            $table->text('notes')->nullable();
            $table->date('due_date')->nullable();

            $table->foreign('employee_id')->references('employee_id')->on('employees')->nullOnDelete();   
            $table->foreign('assigned_to')->references('employee_id')->on('employees')->nullOnDelete();
            $table->foreign('assigned_by')->references('employee_id')->on('employees')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
