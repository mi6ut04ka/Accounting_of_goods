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
        'candle_id', 'volume', 'fragrance', 'fragrance_percentage', 'container_color', 'box_size', 'decor_description'
    ];

    public function candle()
    {
        return $this->belongsTo(Candle::class);
    }

    public function getFullNameAttribute()
    {
        $_volume = (int)$this->volume;
        $volume = $_volume ? "{$_volume} мл" : "Объем не указан";
        $fragrance = $this->fragrance ? "аромат «{$this->fragrance}»" : "без аромата";

        return "{$volume}, {$fragrance}";
    }

}

