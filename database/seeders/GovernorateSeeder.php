<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $governorates = [
            'القاهرة',
            'الجيزة',
            'الإسكندرية',
            'الدقهلية',
            'الشرقية',
            'القليوبية',
            'أسوان',
            'السويس',
        ];

        foreach ($governorates as $name) {
            Governorate::create(['name' => $name]);
        }
    }
}
