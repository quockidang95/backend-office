<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name', 'image', 'description', 'price', 'is_report',
        'price_L', 'product_code', 'category_id', 'price_delivery'
    ];

    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }

}
