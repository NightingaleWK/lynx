<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    use HasFactory;

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'name',
        'en_name',
        'alias',
        'description',
        'material_level_id'
    ];

    /**
     * 与物料分类的关联
     */
    public function materialLevel(): BelongsTo
    {
        return $this->belongsTo(MaterialLevel::class);
    }

    /**
     * 与单位的多对多关联
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'materials_units')
            ->withTimestamps();
    }
}
