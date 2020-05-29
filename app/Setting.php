<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['discount_point', 'discount_user', 'is_payment_delivery',
        'version_ios',
        'version_android',
    ];
    public $timestamps = false;

}
