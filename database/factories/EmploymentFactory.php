<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;
use App\Models\Department;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employment>
 */
class EmploymentFactory extends Factory
{   
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get all department IDs that already exist
        $departmentIds = Department::pluck('id')->toArray();
        $employmentStatus = fake()->randomElement(['active', 'probation', 'suspended', 'resigned', 'terminated']);

        return [
            'employee_id' => Employee::inRandomOrder()->value('employee_id'),
            'department_id'     => fake()->randomElement($departmentIds),
            'employment_type' => fake()->randomElement(['full_time', 'part_time', 'contract', 'intern']),
            'employment_status' => $employmentStatus,
            'company_branch' => fake()->randomElement(['AHG', 'D-8CEFC']),
            'report_to' => null, // Will set this in seeder to avoid circular dependency
            'position'          => fake()->jobTitle(),

            'date_of_employment'       => fake()->dateTimeBetween('-5 years', 'now'),
            'probation_start' => $employmentStatus === 'probation' ? fake()->dateTimeBetween('-2 months', 'now') : null,
            'probation_end' => $employmentStatus === 'probation' ? fake()->dateTimeBetween('now', '+2 months') : null,
            'suspension_start' => $employmentStatus === 'suspended' ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'suspension_end' => $employmentStatus === 'suspended' ? fake()->dateTimeBetween('now', '+1 month') : null,
            'resignation_date' => $employmentStatus === 'resigned' ? fake()->dateTimeBetween('-6 months', 'now') : null,
            'last_working_day' => $employmentStatus === 'resigned' || $employmentStatus === 'terminated' ? fake()->dateTimeBetween('-6 months', 'now') : null,
            'termination_date' => $employmentStatus === 'terminated' ? fake()->dateTimeBetween('-6 months', 'now') : null,
            'work_start_time' => fake()->randomElement(['08:30', '09:00']),
            'work_end_time' => fake()->randomElement(['17:30', '18:00']),
        ];
    }
}
