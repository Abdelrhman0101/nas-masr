<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryBanner extends Model
{
    protected $fillable = ['slug', 'banner_path', 'is_active'];
}
