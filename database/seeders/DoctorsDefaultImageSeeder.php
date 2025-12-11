<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class DoctorsDefaultImageSeeder extends Seeder
{
    public function run(): void
    {
        SystemSetting::updateOrCreate(
            ['key' => 'doctors_default_image'],
            [
                'value' => 'defaults/doctors_default.png',
                'type' => 'string',
                'group' => 'appearance',
                'autoload' => true,
            ]
        );
    }
}
