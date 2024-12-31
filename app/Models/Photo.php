<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'url', 'raw_id', 'set_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function raw()
    {
        return $this->hasMany(Raw::class);
    }

    public function set()
    {
        return $this->hasMany(Set::class);
    }
}
