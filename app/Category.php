<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

     protected $fillable = [
        'name',
    ];
    public $timestamps = false;
    public function products()
    {
        return $this->hasMany('App\Product', 'category_id');
    }
}
