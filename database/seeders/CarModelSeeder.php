<?php

namespace Database\Seeders;

use App\Models\CarModel;
use App\Models\Make;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toyota = Make::where('name', 'Toyota')->first();
        $hyundai = Make::where('name', 'Hyundai')->first();

        if ($toyota) {
            CarModel::insert([
                ['name' => 'Corolla', 'make_id' => $toyota->id],
                ['name' => 'Camry', 'make_id' => $toyota->id],
            ]);
        }

        if ($hyundai) {
            CarModel::insert([
                ['name' => 'Elantra', 'make_id' => $hyundai->id],
                ['name' => 'Tucson', 'make_id' => $hyundai->id],
            ]);
        }
    }

}
