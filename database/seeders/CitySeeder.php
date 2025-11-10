<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Governorate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cairo = Governorate::where('name', 'القاهرة')->first();
        $giza = Governorate::where('name', 'الجيزة')->first();

        if ($cairo) {
            City::insert([
                ['name' => 'مدينة نصر', 'governorate_id' => $cairo->id],
                ['name' => 'مصر الجديدة', 'governorate_id' => $cairo->id],
            ]);
        }

        if ($giza) {
            City::insert([
                ['name' => 'الدقي', 'governorate_id' => $giza->id],
                ['name' => '6 أكتوبر', 'governorate_id' => $giza->id],
            ]);
        }
    }

}
