<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_eaten_at' => 'datetime',
        'frequency' => 'integer',
        'category_id' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'dish_ingredients')
            ->using(DishIngredient::class)
            ->withPivot(['quantity', 'unit', 'remark']);
    }

    public function dishIngredients()
    {
        return $this->hasMany(DishIngredient::class);
    }
}
