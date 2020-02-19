<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
   protected $table = 'feedbacks';

    protected $fillable = [
       'customer_id',
       'body',
       'craeted_at'
   ];
   public $timestamps = false;
}
