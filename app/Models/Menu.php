<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'order_count',
        'view_count',
        'menu_level_id',
        'is_visible',
        'sort_order',
    ];

    /**
     * 属性转换
     */
    protected $casts = [
        'is_visible' => 'boolean',
        'order_count' => 'integer',
        'view_count' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * 与菜谱分类的关联
     */
    public function menuLevel(): BelongsTo
    {
        return $this->belongsTo(MenuLevel::class);
    }

    /**
     * 与物料的多对多关联（通过中间表）
     */
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'menu_materials')
            ->withPivot('unit_id', 'quantity')
            ->withTimestamps();
    }

    /**
     * 与菜单物料的关联
     */
    public function menuMaterials(): HasMany
    {
        return $this->hasMany(MenuMaterial::class);
    }

    /**
     * 默认排序
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'desc');
    }

    /**
     * 只显示可见的菜谱
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * 按分类筛选
     */
    public function scopeByLevel($query, $levelId)
    {
        return $query->where('menu_level_id', $levelId);
    }

    /**
     * 按点菜次数排序
     */
    public function scopePopular($query)
    {
        return $query->orderBy('order_count', 'desc');
    }

    /**
     * 按浏览次数排序
     */
    public function scopeMostViewed($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    /**
     * 增加点菜次数
     */
    public function incrementOrderCount()
    {
        $this->increment('order_count');
    }

    /**
     * 增加浏览次数
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }
}
