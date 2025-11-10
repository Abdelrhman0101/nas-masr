<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryField extends Model
{
    protected $table = 'category_fields';

    protected $fillable = [
        'category_slug',
        'field_name',
        'display_name',
        'type',
        'required',
        'filterable',
        'options',
        'rules_json',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'rules_json' => 'array',
        'required' => 'boolean',
        'filterable' => 'boolean',
        'is_active' => 'boolean',
    ];
}
