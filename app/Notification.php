<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    public $timestamps = false;
    protected $fillable = [
        'title',
        'body',
        'type_notifi',
        'created_at',
        'customer_id',
    ];
}
