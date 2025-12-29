<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class, // Seed fixed roles first (so the FK in users->role_id is valid)
            AdminSeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
            EmploymentSeeder::class,
            ProjectSeeder::class, // Seed project first
            TaskSeeder::class,
            AttendanceSeeder::class,
            EventSeeder::class,
            LeaveEntitlementSeeder::class, // Seed fixed leave types & entitlement first 
            LeaveSeeder::class,
            AnnouncementSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
