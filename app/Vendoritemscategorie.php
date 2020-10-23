<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendoritemscategorie extends Model
{
    protected $guarded = [];

    function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
