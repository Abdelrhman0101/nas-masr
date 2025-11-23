<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserPackages;
use App\Models\Listing;
use Illuminate\Support\Facades\DB;

class ExpirePackageAdsCommand extends Command
{
    protected $signature = 'ads:expire-packages';
    protected $description = 'Expire user listings when their plan (featured/standard) is expired';

    public function handle(): int
    {
        $now = now();

        $expiredFeatured = 0;
        $expiredStandard = 0;

        // هنعدّي على الباقات اللي عندها أي خطة انتهت
        UserPackages::query()
            ->select('id','user_id','featured_expire_date','standard_expire_date')
            ->where(function ($q) use ($now) {
                $q->whereNotNull('featured_expire_date')->where('featured_expire_date', '<', $now)
                  ->orWhere(function ($qq) use ($now) {
                      $qq->whereNotNull('standard_expire_date')->where('standard_expire_date', '<', $now);
                  });
            })
            ->orderBy('id')
            ->chunkById(200, function ($packages) use ($now, &$expiredFeatured, &$expiredStandard) {
                foreach ($packages as $pkg) {
                    // لو featured منتهية
                    if ($pkg->featured_expire_date && $pkg->featured_expire_date < $now) {
                        $expiredFeatured += Listing::where('user_id', $pkg->user_id)
                            ->where('plan_type', 'featured')
                            ->whereIn('status', ['Valid','Pending'])   
                            ->update(['status' => 'Expired']);
                    }

                    // لو standard منتهية
                    if ($pkg->standard_expire_date && $pkg->standard_expire_date < $now) {
                        $expiredStandard += Listing::where('user_id', $pkg->user_id)
                            ->where('plan_type', 'standard')
                            ->whereIn('status', ['Valid','Pending'])
                            ->update(['status' => 'Expired']);
                    }
                }
            });

        $this->info("Expired listings => featured: {$expiredFeatured}, standard: {$expiredStandard}");
        return self::SUCCESS;
    }
}
