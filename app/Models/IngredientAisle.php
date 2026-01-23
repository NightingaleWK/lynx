<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientAisle extends Model
{
    protected $guarded = [];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'aisle_id');
    }
}
