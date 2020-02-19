<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'store_code',
        'table',
        'total_price',
        'customer_id',
        'order_here',
        'address',
        'order_date',
        'note',
        'created_by',
        'point',
        'discount',
        'order_code',
        'price',
        'status',
        'payment_method'
    ];

    public $timestamps = true;

    public function orderitems(){
        return $this->hasMany('App\OrderItem', 'order_id');
    }
     public function customer(){
        return $this->belongsTo('App\User', 'customer_id');
    }
}
