<?php

namespace App\Models;

use App\Traits\HasProductAttributesGyps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vase extends Model
{
    use HasFactory;
    use HasProductAttributesGyps;

    protected $fillable = [
        'gypsum_product_id', 'color', 'gypsum_weight', 'name'
    ];


    public function gypsumProduct()
    {
        return $this->belongsTo(GypsumProduct::class);
    }

    public function getFullNameAttribute()
    {
        $color = $this->color?: 'без цвета';
        $name = $this->name?: 'Без имени';
        return "{$name},{$color}";
    }
}
