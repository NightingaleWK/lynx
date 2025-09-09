<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishing extends Model
{
    /** @use HasFactory<\Database\Factories\WishingFactory> */
    use HasFactory;

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'user_id',
        'content',
        'status',
        'response',
        'accepted_at',
        'fulfilled_at',
        'rejected_at',
    ];

    /**
     * 属性转换
     */
    protected $casts = [
        'accepted_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'rejected_at' => 'datetime',
        'status' => 'string',
    ];

    /**
     * 许愿状态常量
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_FULFILLED = 'fulfilled';
    public const STATUS_REJECTED = 'rejected';

    /**
     * 获取所有状态选项
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => '⏳ 待回应',
            self::STATUS_ACCEPTED => '✅ 已受理',
            self::STATUS_FULFILLED => '🎉 已实现',
            self::STATUS_REJECTED => '❌ 已抛弃',
        ];
    }

    /**
     * 获取纯文本状态选项（不带图标）
     */
    public static function getStatusOptionsText(): array
    {
        return [
            self::STATUS_PENDING => '待回应',
            self::STATUS_ACCEPTED => '已受理',
            self::STATUS_FULFILLED => '已实现',
            self::STATUS_REJECTED => '已抛弃',
        ];
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
     * 待回应许愿
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * 已受理许愿
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    /**
     * 已实现许愿
     */
    public function scopeFulfilled($query)
    {
        return $query->where('status', self::STATUS_FULFILLED);
    }

    /**
     * 已抛弃许愿
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * 与用户的关联
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 按用户筛选
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * 按创建时间排序
     */
    public function scopeOrderByCreated($query, $direction = 'desc')
    {
        return $query->orderBy('created_at', $direction);
    }

    /**
     * 受理许愿
     */
    public function accept($response = null): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'response' => $response,
            'accepted_at' => now(),
        ]);

        return true;
    }

    /**
     * 实现许愿
     */
    public function fulfill(): bool
    {
        if ($this->status !== self::STATUS_ACCEPTED) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_FULFILLED,
            'fulfilled_at' => now(),
        ]);

        return true;
    }

    /**
     * 抛弃许愿
     */
    public function reject(): bool
    {
        if (!in_array($this->status, [self::STATUS_PENDING, self::STATUS_ACCEPTED])) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_at' => now(),
        ]);

        return true;
    }

    /**
     * 检查是否可以受理
     */
    public function canBeAccepted(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * 检查是否可以实现
     */
    public function canBeFulfilled(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    /**
     * 检查是否可以抛弃
     */
    public function canBeRejected(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_ACCEPTED]);
    }
}
