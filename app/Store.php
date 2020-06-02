<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    protected $table = 'stores';

    protected $hidden = [
        'name', 'address','pass_wifi','open_hours',
    ];
}
