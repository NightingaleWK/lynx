<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $guarded = [];

    public function aisle()
    {
        return $this->belongsTo(IngredientAisle::class, 'aisle_id');
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_ingredients')
            ->using(DishIngredient::class)
            ->withPivot(['quantity', 'unit', 'remark']);
    }
}
