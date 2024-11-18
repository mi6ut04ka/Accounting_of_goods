<?php
namespace App\Traits;

use App\Models\GypsumProduct;
use App\Models\Product;

trait HasProductAttributesGyps
{
    public function product()
    {
        return $this->hasOneThrough(Product::class, GypsumProduct::class, 'id', 'id', 'gypsum_product_id', 'product_id');
    }

    public function getPriceAttribute()
    {
        return $this->product->price ?? null;
    }

    public function getCostAttribute()
    {
        return $this->product->cost ?? null;
    }

    public function getSoldAttribute()
    {
        return $this->product->sold ?? 0;
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
