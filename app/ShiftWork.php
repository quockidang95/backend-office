<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftWork extends Model
{
    protected $table = 'shiftworks';

    protected $fillable = [
        'name_shift',
        'name_admin',
        'surplus_box',
        'total_revenue',
        'revenue_online',
        'revenue_cash',
        'created_at',
        'store_code',
        'end_balance_shift'
    ];

    public $timestamps = false;
}
