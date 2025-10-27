<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventRegistration>
 */
class EventRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attendeeType = $this->faker->randomElement(['employee', 'guest']);

        if ($attendeeType === 'employee') {
            $userId = User::inRandomOrder()->first()->id ?? User::factory()->create()->id;
            $guestName = null;
            $guestEmail = null;
            $guestPhone = null;
        } else {
            $userId = null;
            $guestName = $this->faker->name();
            $guestEmail = $this->faker->safeEmail();
            $guestPhone = $this->faker->phoneNumber();
        }
        return [
            'event_id' => Event::inRandomOrder()->first()->id ?? Event::factory()->create()->id,
            'user_id' => $userId,
            'guest_name' => $guestName,
            'guest_email' => $guestEmail,
            'guest_phone' => $guestPhone,
            'attendee_type' => $attendeeType,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'attended']),
            'notes' => $this->faker->boolean(30) ? $this->faker->sentence() : null,
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
