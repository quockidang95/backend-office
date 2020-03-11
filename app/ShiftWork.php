<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftWork extends Model
{
    protected $table = 'shiftworks';

    protected $fillable = [
        'name_shift',
        'name_admin',
        'type_shift',
        'surplus_box',
        'total_revenue',
        'revenue_online',
        'revenue_cash',
        'created_at',
        'store_code'
    ];

    public $timestamps = false;
}
