<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'price', 'cost', 'in_stock', 'description'
    ];

    public function candle(): HasOne
    {
        return $this->hasOne(Candle::class);
    }

    public function gypsumProduct(): HasOne
    {
        return $this->hasOne(GypsumProduct::class);
    }

    public function set(): HasOne
    {
        return $this->hasOne(Set::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id', 'id');
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

        if ($this->set){
            return $this->set->name;
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
    public function getSpecificAttributesAttribute()
    {
        if ($this->candle) {
            if ($this->candle->containerCandle) {
                return [
                    'Объем' => "{$this->candle->containerCandle->volume} мл",
                    'Аромат' => $this->candle->containerCandle->fragrance ?: 'Без аромата',
                    'Цвет контейнера' => $this->candle->containerCandle->container_color ?: 'Не указан',
                    'Тип воска' => $this->candle->containerCandle->type_of_wax ?: 'Не указан',
                ];
            }

            if ($this->candle->moldedCandle) {
                return [
                    'Вес воска' => "{$this->candle->moldedCandle->wax_weight} г",
                    'Аромат' => $this->candle->moldedCandle->fragrance ?: 'Без аромата',
                    'Название' => $this->candle->moldedCandle->name ?: 'Без названия',
                ];
            }
        }
        if ($this->gypsumProduct) {
            if ($this->gypsumProduct->stand) {
                return [
                    'Тип подставки' => $this->gypsumProduct->stand->stand_type ?: 'Без типа',
                    'Цвет' => $this->gypsumProduct->stand->color ?: 'Не указан',
                    'Вес гипса' => "{$this->gypsumProduct->stand->gypsum_weight} г",
                ];
            }

            if ($this->gypsumProduct->vase) {
                return [
                    'Название' => $this->gypsumProduct->vase->name ?: 'Без названия',
                    'Цвет' => $this->gypsumProduct->vase->color ?: 'Не указан',
                    'Вес гипса' => "{$this->gypsumProduct->vase->gypsum_weight} г",
                ];
            }

            if ($this->gypsumProduct->statue) {
                return [
                    'Тип статуи' => $this->gypsumProduct->statue->statue_type ?: 'Без типа',
                    'Цвет' => $this->gypsumProduct->statue->color ?: 'Не указан',
                    'Вес гипса' => "{$this->gypsumProduct->statue->gypsum_weight} г",
                ];
            }
        }
        return ['Сообщение' => 'Нет данных для отображения'];
    }

}
