<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewItem extends Model
{
    protected $guarded = [];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
