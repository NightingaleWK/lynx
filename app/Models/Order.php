<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'order_number',
        'dining_time',
        'remarks',
        'status',
    ];

    /**
     * 属性转换
     */
    protected $casts = [
        'dining_time' => 'datetime',
        'status' => 'string',
    ];

    /**
     * 订单状态常量
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COOKING = 'cooking';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * 获取所有状态选项
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => '⏳ 待确认',
            self::STATUS_CONFIRMED => '✅ 已确认',
            self::STATUS_COOKING => '🍳 制作中',
            self::STATUS_COMPLETED => '🎉 已完成',
            self::STATUS_CANCELLED => '❌ 已取消',
        ];
    }

    /**
     * 获取纯文本状态选项（不带图标）
     */
    public static function getStatusOptionsText(): array
    {
        return [
            self::STATUS_PENDING => '待确认',
            self::STATUS_CONFIRMED => '已确认',
            self::STATUS_COOKING => '制作中',
            self::STATUS_COMPLETED => '已完成',
            self::STATUS_CANCELLED => '已取消',
        ];
    }

    /**
     * 与订单项的关联
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * 生成订单号
     */
    public static function generateOrderNumber(): string
    {
        return 'ORD' . date('YmdHis') . str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
    }

    /**
     * 获取状态标签
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * 按状态筛选
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * 按用餐时间排序
     */
    public function scopeOrderByDiningTime($query, $direction = 'asc')
    {
        return $query->orderBy('dining_time', $direction);
    }

    /**
     * 今日订单
     */
    public function scopeToday($query)
    {
        return $query->whereDate('dining_time', today());
    }

    /**
     * 待处理订单
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * 进行中的订单
     */
    public function scopeInProgress($query)
    {
        return $query->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_COOKING]);
    }
}
