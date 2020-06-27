<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionProduct extends Model
{
    protected $table = 'promotion_products';
    protected $fillable = [
        'product_id', 'promotion_id',
    ];
    public $timestamps = false;
}
