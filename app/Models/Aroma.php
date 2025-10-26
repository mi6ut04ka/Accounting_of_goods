<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aroma extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'top_notes', 'middle_notes', 'base_notes',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('in_stock')
            ->withTimestamps();
    }
}
