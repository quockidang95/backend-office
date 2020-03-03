<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name', 'image', 'description', 'price', 'is_report',
        'price_L', 'product_code'
    ];

    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }

}
