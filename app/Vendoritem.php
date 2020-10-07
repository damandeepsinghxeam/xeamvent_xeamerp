<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendoritem extends Model
{
    protected $guarded = [];

    function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
