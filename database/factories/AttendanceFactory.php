<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // pick a random workday in the past month
        $date = $this->faker->dateTimeBetween('-1 month', 'now');
        $timeIn = Carbon::instance($date)->setTime($this->faker->numberBetween(7, 10), $this->faker->numberBetween(0, 59));
        $workStart = Carbon::instance($date)->setTime(8, 30);

        $statusTimeIn = $timeIn->greaterThan($workStart) ? 'Late' : 'On Time';
        $status = $this->faker->randomElement(['on-site', 'off-site']);

        // 70% chance employee punched out
        $timeOut = $this->faker->boolean(70)
            ? Carbon::instance($date)->setTime($this->faker->numberBetween(16, 19), $this->faker->numberBetween(0, 59))
            : null;

        $statusTimeOut = $timeOut
            ? ($timeOut->lt(Carbon::instance($date)->setTime(17, 30)) ? 'Early Leave' : 'On Time')
            : null;

        return [
            // Randomly decide if the employee is present, absent, or on leave
            'employee_id'       => Employee::inRandomOrder()->value('employee_id'),
            'date'              => $timeIn->toDateString(),
            'time_in'           => $timeIn->toTimeString(),
            'time_out'          => $timeOut?->toTimeString(),
            'location'          => $this->faker->latitude(3.0, 3.3) . ',' . $this->faker->longitude(101.6, 101.8),
            'status'            => $status,
            'status_time_in'    => $statusTimeIn,
            'status_time_out'   => $statusTimeOut,
            'late_reason'        => fake()->optional()->sentence(),
            'early_leave_reason' => fake()->optional()->sentence(),
        ];
    }
}
