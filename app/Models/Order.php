<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name', 'order_date', 'deadline_date', 'order_status', 'note', 'pay_status', 'order_number', 'user_id'];

    public static function getStatusName($status)
    {
        $statuses = [
            'pending' => 'Ожидает обработки',
            'processing' => 'В обработке',
            'completed' => 'Завершен',
            'issued' => 'Выдан',
            'canceled' => 'Отменен',
        ];

        return $statuses[$status] ?? $status;
    }
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($order) {
            if ($order->isDirty('order_status')) {
                if ($order->user) {
                    $order->user->notifications()->create([
                        'title' => 'Статус заказа изменён',
                        'body' => 'Статус вашего заказ #' . $order->order_number . ' изменен на ' . Order::getStatusName($order->order_status),
                    ]);
                }
            }
        });
    }
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function sails(): HasMany
    {
        return $this->HasMany(Sale::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
