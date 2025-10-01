<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\LeaveEntitlement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveEntitlementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leave_entitlements')->insert([
            ['leave_type' => 'annual_leave', 'full_entitlement' => 14],
            ['leave_type' => 'medical_leave', 'full_entitlement' => 14],
            ['leave_type' => 'emergency_leave', 'full_entitlement' => 3],
            ['leave_type' => 'hospitalization', 'full_entitlement' => 60],
            ['leave_type' => 'maternity', 'full_entitlement' => 98],
            ['leave_type' => 'compassionate', 'full_entitlement' => 3],
            ['leave_type' => 'replacement', 'full_entitlement' => 10],
            ['leave_type' => 'unpaid_leave', 'full_entitlement' => 90],
            ['leave_type' => 'marriage', 'full_entitlement' => 7],
        ]);
    }
}
