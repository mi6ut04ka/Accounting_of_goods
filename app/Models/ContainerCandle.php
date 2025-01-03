<?php

namespace App\Models;

use App\Traits\HasProductAttributesCandle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerCandle extends Model
{
    use HasFactory;
    use HasProductAttributesCandle;

    protected $fillable = [
        'candle_id', 'volume', 'fragrance', 'fragrance_percentage', 'container_color', 'box_size', 'decor_description','type_of_wax'
    ];

    public function candle()
    {
        return $this->belongsTo(Candle::class);
    }

    public function getFullNameAttribute(): string
    {
        $_volume = (int)$this->volume;
        $volume = $_volume ? "{$_volume} мл" : "Объем не указан";
        $fragrance = $this->fragrance ? "аромат «{$this->fragrance}»" : "без аромата";
        $color = $this->container_color;

        return "{$volume}, {$fragrance}, {$color}";
    }

}

