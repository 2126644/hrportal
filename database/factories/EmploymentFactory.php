<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;
use App\Models\User;
use App\Models\Employment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employment>
 */
class EmploymentFactory extends Factory
{
    protected $model = Employment::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $employmentStatus = fake()->randomElement(['active', 'probation', 'suspended', 'resigned', 'terminated']);

        return [
            'department'        => fake()->randomElement(['HR', 'IT', 'Finance', 'Marketing', 'Development']),
            'position'          => fake()->jobTitle(),
            'date_joined'       => fake()->dateTimeBetween('-5 years', 'now'),

            'employee_id' => Employee::factory(),
            'employment_type' => fake()->randomElement(['full_time', 'part_time', 'contract', 'intern']),
            'employment_status' => $employmentStatus,
            'company_branch' => fake()->randomElement(['AHG', 'D-8CEFC']),
            'report_to' => null, // Will set this in seeder to avoid circular dependency
            'probation_start' => $employmentStatus === 'probation' ? fake()->dateTimeBetween('-2 months', 'now') : null,
            'probation_end' => $employmentStatus === 'probation' ? fake()->dateTimeBetween('now', '+2 months') : null,
            'suspended_start' => $employmentStatus === 'suspended' ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'suspended_end' => $employmentStatus === 'suspended' ? fake()->dateTimeBetween('now', '+1 month') : null,
            'resigned_date' => $employmentStatus === 'resigned' ? fake()->dateTimeBetween('-6 months', 'now') : null,
            'termination_date' => $employmentStatus === 'terminated' ? fake()->dateTimeBetween('-6 months', 'now') : null,
        ];
    }
}
