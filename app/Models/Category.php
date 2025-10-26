<?php

namespace App\Models;

use Cocur\Slugify\Slugify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'slug', 'is_final', 'is_set','is_visible', 'type', 'description'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $slugify = new Slugify();
            $category->slug = $slugify->slugify($category->name);
        });
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function sets()
    {
        return $this->hasMany(SetItem::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function photo()
    {
        return $this->hasOne(Photo::class);
    }

}

