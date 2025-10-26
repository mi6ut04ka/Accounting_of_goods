<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raw extends Model
{
    protected $fillable = ['name', 'price', 'link', 'category_id'];
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(RawAttributeValue::class);
    }

    public function photo()
    {
        return $this->hasOne(Photo::class);
    }

    public function attributes()
    {
        return $this->hasManyThrough(
            ProductAttribute::class,
            RawAttributeValue::class,
            'raw_id',
            'id',
            'id',
            'attribute_id'
        );
    }
}
