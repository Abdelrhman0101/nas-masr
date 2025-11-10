<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            ['id' => 1, 'slug' => 'cars', 'name' => 'سيارات', 'is_active' => true],
            ['id' => 2, 'slug' => 'cars_rent', 'name' => 'تأجير سيارات', 'is_active' => true],
            ['id' => 3, 'slug' => 'real_estate', 'name' => 'عقارات', 'is_active' => true],
            ['id' => 4, 'slug' => 'animals', 'name' => 'حيوانات', 'is_active' => true],
            ['id' => 5, 'slug' => 'jobs', 'name' => 'وظائف', 'is_active' => true],
        ];

        foreach ($rows as $row) {
            DB::table('categories')->updateOrInsert(
                ['id' => $row['id']],
                $row + ['created_at' => $now, 'updated_at' => $now]
            );
        }

    }
}
