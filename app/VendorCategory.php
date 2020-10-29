<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    protected $guarded = [];

    function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
    function vendors()
    {
        return $this->belongsToMany('App\Vendor')->withTimestamps();
    }
}
