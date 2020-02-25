<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
        'size',
        'recipe'
    ];
    public $timestamps = false;

    public function product(){
        return $this->belongsTo('App\Product', 'product_id');
    }
}
