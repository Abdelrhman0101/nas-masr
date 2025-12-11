<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class TeachersDefaultImageSeeder extends Seeder
{
    public function run(): void
    {
        SystemSetting::updateOrCreate(
            ['key' => 'teachers_default_image'],
            [
                'value' => 'defaults/teachers_default.png',
                'type' => 'string',
                'group' => 'appearance',
                'autoload' => true,
            ]
        );
    }
}
