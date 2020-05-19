<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';

    protected $fillable = [
        'title',
        'body',
        'promotion_code',
        'adjuster',
        'start_date',
        'end_date',
        'created_at',
        'status', 'start_hour', 'end_hour',
    ];

    public $timestamps = false;
}
