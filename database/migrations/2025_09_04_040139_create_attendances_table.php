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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->date('date');
            $table->time('time_in')->nullable();
            $table->string('location')->nullable();
            $table->time('time_out')->nullable();
            $table->string('status_time_in')->nullable();  // on-time, late
            $table->string('status_time_out')->nullable(); // early, normal
            $table->string('late_reason')->nullable();
            $table->string('early_leave_reason')->nullable();
            $table->enum('status', ['on-site', 'off-site', 'leave'])->default('on-site');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
