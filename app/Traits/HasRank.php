<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasRank
{

    public function getNextRank(string $modelClass, int $categoryId): int
    {
        return DB::transaction(function () use ($modelClass, $categoryId) {
            $table = (new $modelClass)->getTable();

            $maxRank = DB::table($table)
                ->where('category_id', $categoryId)
                ->lockForUpdate()
                ->max('rank');

            return ($maxRank ?? 0) + 1;
        });
    }


    public function makeRankOne($modelClass, $adId): bool
    {
        return DB::transaction(function () use ($modelClass, $adId) {
            $model = new $modelClass;
            $table = $model->getTable();
            DB::table($table)->lockForUpdate()->get();

            $ad = $modelClass::where('id', $adId)->lockForUpdate()->first();
            if (!$ad)
                return false;

            DB::table($table)->increment('rank');
            $ad->update(['rank' => 1]);

            return true;
        });
    }
}
