<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unit extends Model
{
    use HasFactory;

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'name',
        'en_name',
        'remark'
    ];

    /**
     * 与物料的多对多关联
     */
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'materials_units')
            ->withTimestamps();
    }
}
