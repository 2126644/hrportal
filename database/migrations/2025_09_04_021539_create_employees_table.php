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
        Schema::create('employees', function (Blueprint $table) {
            $table->string('employee_id')->primary();    // staff number, matric, etc. custom PK
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable()->unique();
            $table->string('address')->nullable();
            $table->string('ic_number')->nullable()->unique();
            $table->string('marital_status')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->string('nationality')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('profile_pic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
