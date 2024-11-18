<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['quantity', 'time_of_sale', 'product_id', 'order_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
