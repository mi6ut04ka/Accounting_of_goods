<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'cost', 'in_stock'
    ];

    public function candle()
    {
        return $this->hasOne(Candle::class);
    }

    public function gypsumProduct()
    {
        return $this->hasOne(GypsumProduct::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function getNameAttribute()
    {
        if ($this->candle) {

            if ($this->candle->moldedCandle) {
                return $this->candle->moldedCandle->full_name;
            }

            if ($this->candle->containerCandle) {
                return $this->candle->containerCandle->full_name;
            }
        }

        if ($this->gypsumProduct) {
            if ($this->gypsumProduct->stand) {
                return $this->gypsumProduct->stand->full_name;
            }

            if ($this->gypsumProduct->vase) {
                return $this->gypsumProduct->vase->full_name;
            }

            if ($this->gypsumProduct->statue) {
                return $this->gypsumProduct->statue->full_name;
            }
        }
    }
    public function decrementStock(int $quantity)
    {
        if ($this->in_stock < $quantity) {
            throw new \Exception("Недостаточно товара '{$this->name}' на складе.");
        }

        $this->decrement('in_stock', $quantity);
    }
    public function incrementStock(int $quantity)
    {
        $this->increment('in_stock', $quantity);
    }
}
