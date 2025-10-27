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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Make nullable
            $table->string('guest_name')->nullable(); // For guest attendees
            $table->string('guest_email')->nullable(); // For guest attendees
            $table->string('guest_phone')->nullable(); // For guest attendees
            $table->enum('attendee_type', ['employee', 'guest'])->default('employee');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'attended'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Update unique constraint to handle guests
            $table->unique(['event_id', 'user_id']);
            $table->unique(['event_id', 'guest_email']); // Prevent duplicate guest registrations
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
