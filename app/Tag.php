<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    public $fillable = ['number_tag', 'status'];

    public $timestamps = false;
}
