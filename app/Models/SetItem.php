<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'contained_product_id',
        'name',
        'cost',
        'quantity',];

    public function containedProduct()
    {
        return $this->belongsTo(Product::class, 'contained_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
