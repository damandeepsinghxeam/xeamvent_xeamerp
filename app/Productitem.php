<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productitem extends Model
{
    protected $guarded = [];
    protected $table = 'product_items';

  /*  function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
    */
}
