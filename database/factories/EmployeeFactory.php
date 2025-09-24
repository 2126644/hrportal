<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id'      => strtoupper(fake()->bothify('EMP###')),
            'user_id'          => User::factory(), // or existing user id
            'full_name'        => fake()->name(),
            'department'       => fake()->randomElement(['HR', 'IT', 'Finance', 'Marketing', 'Development']),
            'position'         => fake()->jobTitle(),
            'email'            => fake()->unique()->safeEmail(),
            'phone_number'     => fake()->unique()->phoneNumber(),
            'address'          => fake()->address(),
            'ic_number'        => fake()->unique()->numerify('############'),
            'marital_status'   => fake()->randomElement(['Single', 'Married']),
            'gender'           => fake()->randomElement(['Male', 'Female']),
            'birthday'         => fake()->date(),
            'nationality'      => fake()->country(),
            'emergency_contact' => fake()->phoneNumber(),
            'profile_pic'      => null,
        ];
    }
}
