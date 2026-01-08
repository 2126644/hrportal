<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['id' => 1, 'department_name' => 'Human Resources'],
            ['id' => 2, 'department_name' => 'Information Technology'],
            ['id' => 3, 'department_name' => 'Finance'],
            ['id' => 4, 'department_name' => 'Marketing'],
            ['id' => 5, 'department_name' => 'Operations'],
            ['id' => 6, 'department_name' => 'Others'],
        ]);
    }
}
