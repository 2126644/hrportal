<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the admin user
        $admin = User::firstOrCreate(
            ['email' => 'tehaiium@gmail.com'], // email of admin
            [
                'name'     => 'System Administrator',
                'password' => Hash::make('password'), // default password
                'role_id'  => 2, 
            ]
        );

        // Optional: give this admin a linked employee profile
        if (! $admin->employee) {
            Employee::create([
                'user_id'    => $admin->id,
                'full_name'  => 'System Administrator',
                'position'   => 'Administrator',
                'department' => 'Management',
                // add any other required employee fields
            ]);
        }
    }
}
