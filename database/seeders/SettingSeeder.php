<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'event_categories'],
            ['value' => json_encode([
                'meeting',
                'conference',
                'workshop',
                'networking',
                'webinar',
                'social',
                'other'
            ])]
        );

        Setting::updateOrCreate(
            ['key' => 'leave_types'],
            ['value' => json_encode([
                'annual',
                'medical',
                'emergency',
                'hospitalization',
                'maternity',
                'compassionate',
                'replacement',
                'unpaid',
                'marriage'
            ])]
        );
    }
}
