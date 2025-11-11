<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('system_settings')->updateOrInsert(
            ['id' => 1],
            [
                'support_number' => '+971 54 519 4553',
                // نحفظ مسار مجلد الصور؛ يمكنك وضع اسم الملف لاحقًا داخل هذا المجلد
                'panner_image'   => 'storage/panner_image/',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]
        );
    }
}
