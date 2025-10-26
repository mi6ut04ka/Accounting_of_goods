<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = ['raw_id', 'attribute_id', 'value'];

    public function raw()
    {
        return $this->belongsTo(Raw::class);
    }

    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }
}
