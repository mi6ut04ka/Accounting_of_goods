<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;
    protected $fillable =
            ['code',
            'discount',
            'is_active',
            'min_order_amount',
            'applicable_categories',
            'usage_limit',
            'used_count',
            'start_date',
            'end_date'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
