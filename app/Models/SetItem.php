<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetItem extends Model
{
    use HasFactory;

    protected $fillable = ['set_id', 'product_id', 'name', 'cost', 'quantity'];

    public function set()
    {
        return $this->belongsTo(Set::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
