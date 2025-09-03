<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'item_remarks',
    ];

    /**
     * 属性转换
     */
    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * 与订单的关联
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 与菜品的关联
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
