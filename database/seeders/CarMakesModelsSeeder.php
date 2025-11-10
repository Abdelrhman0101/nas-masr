<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarMakesModelsSeeder extends Seeder
{
    public function run(): void
    {
        $makes = [
            'هيونداي'   => ['إلنترا', 'أكسنت', 'توسان', 'سوناتا'],
            'كيا'       => ['سيراتو', 'سبورتاج', 'بيكانتو', 'كارنفال'],
            'تويوتا'    => ['كورولا', 'يارس', 'كامري', 'راف 4'],
            'نيسان'     => ['صني', 'قشقاي', 'سنترا'],
            'شيفروليه'  => ['أفيو', 'أوبترا', 'كابتيفا'],
            'بي إم دبليو' => ['320i', 'X5', 'X3'],
            'مرسيدس'    => ['C200', 'E200', 'GLC'],
        ];

        foreach ($makes as $makeName => $models) {
            $makeId = DB::table('makes')->where('name', $makeName)->value('id');

            if (!$makeId) {
                $makeId = DB::table('makes')->insertGetId([
                    'name' => $makeName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($models as $modelName) {
                $exists = DB::table('models')
                    ->where('make_id', $makeId)
                    ->where('name', $modelName)
                    ->exists();

                if (!$exists) {
                    DB::table('models')->insert([
                        'make_id' => $makeId,
                        'name' => $modelName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
