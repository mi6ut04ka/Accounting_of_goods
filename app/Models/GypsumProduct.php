<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GypsumProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'type'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stand()
    {
        return $this->hasOne(Stand::class);
    }

    public function vase()
    {
        return $this->hasOne(Vase::class);
    }

    public function statue()
    {
        return $this->hasOne(Statue::class);
    }
}
