<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\Employee;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by'    => Employee::inRandomOrder()->first()->employee_id,
            'event_name'    => fake()->catchPhrase(),
            'description'   => fake()->paragraph(),
            'event_date'    => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'event_time'    => fake()->time('H:i:s'),
            'event_location' => fake()->city(),
            'category'      => fake()->randomElement(['meeting', 'conference', 'workshop', 'networking', 'webinar', 'social', 'other']),
            'capacity'      => fake()->numberBetween(10, 200),
            'attendees'     => fake()->numberBetween(0, 200),
            'price'         => fake()->randomFloat(2, 0, 100),
            'image'         => null, // or fake()->imageUrl() for a random image URL
            'event_status'  => fake()->randomElement(['upcoming', 'ongoing', 'completed', 'cancelled']),
            'organizer'     => fake()->company(),
            'tags'          => json_encode(fake()->words(3)),
            'rsvp_required' => fake()->boolean(),
        ];
    }
}
