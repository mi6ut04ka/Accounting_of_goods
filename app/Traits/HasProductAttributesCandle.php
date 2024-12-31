<?php
namespace App\Traits;

use App\Models\Candle;
use App\Models\Product;

trait HasProductAttributesCandle
{
    public function product()
    {
        return $this->hasOneThrough(Product::class, Candle::class, 'id', 'id', 'candle_id', 'product_id');
    }

    public function getPriceAttribute()
    {
        return (int)$this->product->price ?? null;
    }

    public function getCostAttribute()
    {
        return (int)$this->product->cost ?? null;
    }

    public function getSoldAttribute()
    {
        return $this->product->sold ?? 0;
    }

    public function getDescriptionAttribute()
    {
        return $this->product->description ?? null;
    }

    public function getInStockAttribute()
    {
        return $this->product->in_stock ?? 0;
    }

   public function getParentIdAttribute()
   {
       return $this->product->id ?? 0;
   }
   public function getPhotosAttribute()
   {
       return $this->product->photos ?? [];
   }
}
