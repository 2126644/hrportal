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
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id'       => strtoupper(fake()->unique()->bothify('EMP###')),
            'user_id'           => User::factory(), // or existing user id
            'full_name'         => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'phone_number'      => fake()->unique()->phoneNumber(),
            'address'           => fake()->address(),
            'ic_number'         => fake()->unique()->numerify('############'),
            'marital_status'    => fake()->randomElement(['Single', 'Married']),
            'gender'            => fake()->randomElement(['Male', 'Female']),
            'birthday'          => fake()->dateTimeBetween('-50 years', '-20 years'),
            'nationality'       => fake()->country(),
            'emergency_contact' => fake()->phoneNumber()
        ];
    }
}
