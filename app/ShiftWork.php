<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftWork extends Model
{
    protected $table = 'shiftworks';

    protected $fillable = [
        'name_shift', 'name_admin', 'price_box', 'created_at', 'store_code'
    ];

    public $timestamps = false;
}
