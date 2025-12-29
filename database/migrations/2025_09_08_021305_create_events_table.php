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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('created_by')->nullable();  // add foreign key column
            $table->string('event_name');
            $table->text('description');
            $table->date('event_date');
            $table->time('event_time');
            $table->string('event_location');
            $table->string('event_category')->default('other');
            $table->integer('capacity');
            $table->integer('attendees')->default(0);
            $table->decimal('price', 8, 2)->default(0);
            $table->string('image')->nullable();
            $table->enum('event_status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->string('organizer');
            $table->json('tags')->nullable();
            $table->boolean('rsvp_required')->default(false);

            $table->foreign('created_by')->references('employee_id')->on('employees')->nullOnDelete();  

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
