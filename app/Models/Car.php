<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Car extends Model
{
    //

    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'user_id',
        'governorate_id',
        'city_id',
        'make_id',
        'model_id',
        'year',
        'kilometers',
        'type',
        'color',
        'fuel_type',
        'transmission',
        'price',
        'contact_phone',
        'whatsapp_phone',
        'description',
        'images',
        'main_image',
        'add_category',
        'add_status',
        'views',
        'rank'

    ];

    protected $casts = [
        'images' => 'array',
        'admin_approved' => 'boolean',
    ];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function make()
    {
        return $this->belongsTo(Make::class);
    }

    public function model()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getMainImageUrlAttribute()
    {
        return $this->main_image ? Storage::url($this->main_image) : null;
    }

    public function getStatusAttribute()
    {
        return $this->add_status;
    }

    public function getSectionAttribute()
    {
        return $this->add_category;
    }


    // ============================
    // 🧭 Scope 
    // ============================

    public function scopeValid(Builder $query): void
    {
        $query->where('add_status', 'Valid');
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('admin_approved', true);
    }

    public function scopeActive(Builder $query): void
    {
        $query->valid()->approved();
    }

    public function scopeMostViewed(Builder $query): void
    {
        $query->orderBy('views', 'desc');
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
    public function scopeFilter($query, $filters)
    {
        return $query
            ->when($filters['governorate'] ?? null, function ($q, $v) {
                $q->whereHas('governorate', fn($sub) => $sub->where('name', 'like', "%{$v}%"));
            })

            ->when($filters['city'] ?? null, function ($q, $v) {
                $q->whereHas('city', fn($sub) => $sub->where('name', 'like', "%{$v}%"));
            })

            ->when($filters['make'] ?? null, function ($q, $v) {
                $q->whereHas('make', fn($sub) => $sub->where('name', 'like', "%{$v}%"));
            })
            ->when($filters['model'] ?? null, function ($q, $v) {
                $q->whereHas('model', fn($sub) => $sub->where('name', 'like', "%{$v}%"));
            })
            ->when($filters['year'] ?? null, fn($q, $v) => $q->where('year', $v))
            ->when($filters['min_km'] ?? null, fn($q, $v) => $q->where('kilometers', '>=', $v))
            ->when($filters['max_km'] ?? null, fn($q, $v) => $q->where('kilometers', '<=', $v));
    }


    public function scopeSearch($query, $term)
    {
        if (!$term)
            return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('type', 'like', "%{$term}%")
                ->orWhere('color', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
                ->orWhereHas('governorate', fn($sub) => $sub->where('name', 'like', "%{$term}%"))
                ->orWhereHas('city', fn($sub) => $sub->where('name', 'like', "%{$term}%"))
                ->orWhereHas('make', fn($sub) => $sub->where('name', 'like', "%{$term}%"))
                ->orWhereHas('model', fn($sub) => $sub->where('name', 'like', "%{$term}%"));
        });
    }

}
