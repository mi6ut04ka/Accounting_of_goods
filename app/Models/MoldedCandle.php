<?php

namespace App\Models;

use App\Traits\HasProductAttributesCandle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoldedCandle extends Model
{
    use HasFactory;
    use HasProductAttributesCandle;

    protected $fillable = [
        'candle_id', 'wax_weight', 'fragrance', 'fragrance_percentage','name'
    ];


    public function candle()
    {
        return $this->belongsTo(Candle::class);
    }

    public function getfullNameAttribute()
    {
        $_wax_weight = (int)$this->wax_weight;
        $name = $this->name?: 'Без названия';
        $fragrance = $this->fragrance? "аромат «{$this->fragrance}»" : "без аромата";
        $wax_weight = $_wax_weight? "$_wax_weight г" : "вес не указан";
        return "$name, $fragrance, $wax_weight";
    }
}
