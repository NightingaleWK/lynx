<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuMaterial extends Model
{
    use HasFactory;

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'menu_id',
        'material_id',
        'unit_id',
        'quantity'
    ];

    /**
     * 属性转换
     */
    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    /**
     * 与菜谱的关联
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * 与物料的关联
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * 与单位的关联
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
