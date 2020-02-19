<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name', 'content', 'image', 'thunb_image', 'description', 'price', 'promotion_price',
        'price_L'
    ];

    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }
}
