<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raw extends Model
{
    protected $fillable = ['name', 'price', 'link'];
    use HasFactory;

    public function attributes()
    {
        return $this->morphMany(RawAttribute::class, 'attributable');
    }

    public function photo()
    {
        return $this->hasOne(Photo::class);
    }
}
