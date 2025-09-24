<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Task;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\Event;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed fixed roles first (so the foreign key in users->role_id is valid)
        $this->call(RoleSeeder::class);

        // safely create the dependent tables: tasks, leaves, attendance, events
        $this->call([
        UserSeeder::class,
        EmployeeSeeder::class,
        TaskSeeder::class,
        AttendanceSeeder::class,
        EventSeeder::class,
        LeaveSeeder::class,
    ]);
    }
}
