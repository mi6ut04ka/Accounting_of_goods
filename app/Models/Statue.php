<?php

namespace App\Models;

use App\Traits\HasProductAttributesGyps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statue extends Model
{
    use HasFactory;
    use HasProductAttributesGyps;

    protected $fillable = [
        'gypsum_product_id', 'statue_type', 'color', 'gypsum_weight'
    ];

    public function gypsumProduct()
    {
        return $this->belongsTo(GypsumProduct::class);
    }
    public function getFullNameAttribute()
    {
        $type = $this->statue_type?: "Без типа";
        $color = $this->color?: "без цвета";

        return "{$type}, {$color}";
    }
}
