<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Leave>
 */
class LeaveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id'   => Employee::inRandomOrder()->first()->employee_id,
            'name'          => fake()->name(),
            'applied_date'  => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'leave_type'    => fake()->randomElement(['annual_leave', 'medical_leave', 'emergency_leave', 'hospitalization', 'maternity', 'compassionate', 'replacement', 'unpaid_leave', 'marriage']),
            'leave_length'  => fake()->randomElement(['full_day', 'AM', 'PM']),
            'reason'        => fake()->optional()->sentence(),
            'start_date'    => $start = fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'end_date'      => fake()->dateTimeBetween($start, '+1 month')->format('Y-m-d'),
            'days'          => fake()->numberBetween(1, 10),
            'attachment'    => null,
            'approved_by'   => fake()->boolean(60) ? User::inRandomOrder()->first()?->id : null,
            'status'        => fake()->randomElement(['pending', 'approved', 'rejected']),
            'reject_reason' => fake()->optional()->sentence(),
            'action'        => fake()->optional()->word(),
        ];
    }
}
