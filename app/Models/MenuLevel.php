<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuLevel extends Model
{
    /** @use HasFactory<\Database\Factories\MenuLevelFactory> */
    use HasFactory;

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'name',
        'sort_order',
        'is_visible',
    ];

    /**
     * 属性转换
     */
    protected $casts = [
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * 默认排序
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'desc');
    }

    /**
     * 只显示可见的分类
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}
