<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingAttribute extends Model
{
    protected $fillable = [
        'key',
        'type',
        'value_string',
        'value_int',
        'value_decimal',
        'value_bool',
        'value_date',
        'value_json'
    ];

    protected $casts = [
        'value_json' => 'array',
        'value_bool' => 'boolean',
        'value_decimal' => 'decimal:4',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
