<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawAttribute extends Model
{
    protected $fillable = ['key', 'value'];
    use HasFactory;


    public function attributable()
    {
        return $this->morphTo();
    }
}
