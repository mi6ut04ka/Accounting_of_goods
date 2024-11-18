<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candle extends Product
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'type'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function moldedCandle()
    {
        return $this->hasOne(MoldedCandle::class);
    }

    public function containerCandle()
    {
        return $this->hasOne(ContainerCandle::class);
    }
}
