<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rechage extends Model
{
    protected $table = 'rechages';
    protected $fillable = [
        'customer_id', 'price', 'created_at', 'created_by', 'store_code', 'money_discount', 'point_discount',
    ];
    public $timestamps = false;

    public function customer(){
        return $this->belongsTo('App\User', 'customer_id');
    }
}
