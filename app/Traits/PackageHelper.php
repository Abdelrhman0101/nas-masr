<?php

namespace App\Traits;

use App\Models\UserPackages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

trait PackageHelper
{
    public function consumeForPlan(int $userId, string $planType, int $count = 1): array
    {
        $planType = $this->normalizePlan($planType);

        $pkg = UserPackages::where('user_id', $userId)->first();

        if (!$pkg) {
            return $this->fail('لا توجد باقة لهذا المستخدم.');
        }

        // if ($pkg->expire_date instanceof Carbon && $pkg->expire_date->isPast()) {
        //     return $this->fail('الباقة منتهية الصلاحية.');
        // }

        [$totalField, $usedField, $daysField, $startField, $expireField, $title] = $this->mapFields($planType);

        if (empty($pkg->{$expireField})) {
            $days = (int) ($pkg->{$daysField} ?? 0);
            if ($days > 0) {
                $pkg->{$startField}  = now();
                $pkg->{$expireField} = now()->copy()->addDays($days);
                $pkg->save();
            }
        }

        if ($pkg->{$expireField} instanceof Carbon && $pkg->{$expireField}->isPast()) {
            return $this->fail("انتهت صلاحية {$title}.");
        }

        $total  = (int) ($pkg->{$totalField} ?? 0);
        $used   = (int) ($pkg->{$usedField} ?? 0);
        $remain = max(0, $total - $used);

        if ($remain < $count) {
            return $this->fail("لا يوجد رصيد كافٍ في {$title} (المتبقي: {$remain}).");
        }

        $pkg->increment($usedField, $count);

        return [
            'success'     => true,
            'message'     => "تم خصم {$count} إعلان من {$title} ✅",
            'plan'        => $planType,
            'total'       => $total,
            'used'        => $used + $count,
            'remaining'   => max(0, $total - ($used + $count)),
            'expire_date' => $pkg->{$expireField},
            'package_id'  => $pkg->id,
        ];
    }


    protected function normalizePlan(string $plan): string
    {
        $plan = strtolower(trim($plan));
        return match ($plan) {
            'premium', 'featured' => 'featured',
            'standard'            => 'standard',
            'free'                => 'free',
            default               => 'standard', // fallback آمن
        };
    }

    protected function mapFields(string $plan): array
    {
        return match ($plan) {
            'featured' => ['featured_ads', 'featured_ads_used', 'featured_days', 'featured_start_date', 'featured_expire_date', 'الباقة المتميزة'],
            'standard' => ['standard_ads', 'standard_ads_used', 'standard_days', 'standard_start_date', 'standard_expire_date', 'الباقة القياسية'],
            default    => ['standard_ads', 'standard_ads_used', 'standard_days', 'standard_start_date', 'standard_expire_date', 'الباقة القياسية'],
        };
    }

    protected function fail(string $msg): array
    {
        return ['success' => false, 'message' => $msg];
    }
}
