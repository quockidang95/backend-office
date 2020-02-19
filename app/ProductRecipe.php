<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRecipe extends Model
{
    protected $table = 'product_recipe';
    protected $fillable = [
        'product_id', 'recipe_id'
    ];
    public $timestamps = false;
}
