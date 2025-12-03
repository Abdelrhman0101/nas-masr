<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPlanPrice extends Model
{
    protected $table = 'category_plan_prices';

    protected $fillable = [
        'category_id',

        'price_featured',      
        'featured_ad_price', 
        'featured_days',  

        'price_standard',      
        'standard_ad_price',   
        'standard_days',       
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
