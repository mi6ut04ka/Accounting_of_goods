<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'price', 'cost', 'in_stock', 'description','name', 'category_id', 'aroma_id'
    ];

    protected $hidden = ['cost'];

    public function aromas(): BelongsToMany
    {
        return $this->belongsToMany(Aroma::class)
            ->withPivot('in_stock')
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function setItems()
    {
        return $this->hasMany(SetItem::class);
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
