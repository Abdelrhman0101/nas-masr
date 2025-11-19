<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class UserPackages extends Model
{
    protected $table = 'user_packages';

    protected $fillable = [
        'user_id',
        'featured_ads',
        'standard_ads',
        'featured_ads_used',
        'standard_ads_used',
        'days',
        'start_date',
        'expire_date',
    ];

    protected $casts = [
        'featured_ads' => 'integer',
        'standard_ads' => 'integer',
        'featured_ads_used' => 'integer',
        'standard_ads_used' => 'integer',
        'days' => 'integer',
        'start_date' => 'datetime',
        'expire_date' => 'datetime',
    ];

    protected $appends = [
        'featured_ads_remaining',
        'standard_ads_remaining',
        'active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('expire_date')->orWhere('expire_date', '>=', now());
        });
    }

    public function getFeaturedAdsRemainingAttribute(): int
    {
        $total = (int) ($this->featured_ads ?? 0);
        $used = (int) ($this->featured_ads_used ?? 0);
        return max(0, $total - $used);
    }

    public function getStandardAdsRemainingAttribute(): int
    {
        $total = (int) ($this->standard_ads ?? 0);
        $used = (int) ($this->standard_ads_used ?? 0);
        return max(0, $total - $used);
    }

    public function getActiveAttribute(): bool
    {
        if ($this->expire_date === null) {
            return true;
        }
        return $this->expire_date->isFuture();
    }

    public function consumeFeatured(): bool
    {
        if ($this->featured_ads_remaining <= 0) {
            return false;
        }
        $this->increment('featured_ads_used');
        return true;
    }

    public function consumeStandard(): bool
    {
        if ($this->standard_ads_remaining <= 0) {
            return false;
        }
        $this->increment('standard_ads_used');
        return true;
    }

    public function startNow(int $days): void
    {
        $this->start_date = now();
        $this->expire_date = now()->addDays($days);
        $this->days = $days;
        $this->save();
    }
}
