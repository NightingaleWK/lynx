<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DishIngredient extends Pivot
{
    protected $guarded = [];
    protected $table = 'dish_ingredients';

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
