<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\Employee;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::inRandomOrder()->first()->employee_id,
            'title'       => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'assigned_to' => Employee::inRandomOrder()->value('employee_id'),
            'assigned_by' => Employee::inRandomOrder()->value('employee_id'),
            'status'      => fake()->randomElement(['to-do', 'in-progress', 'in-review', 'completed']),
            'notes'       => fake()->optional()->sentence(),
            'due_date'    => fake()->optional()->dateTimeBetween('now', '+2 months')?->format('Y-m-d'),
        ];
    }
}
