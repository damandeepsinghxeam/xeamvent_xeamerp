<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorApprovals extends Model
{
    protected $guarded = [];

    function user()
    {
    	return $this->belongsTo('App\User');
    }

    function vendor(){

        return $this->hasOne('App\Vendor');
    }

    // function notifications()
    // {
    //     return $this->morphMany('App\Notification', 'notificationable');
    // }

    // function appliedVendor()
    // {
    //     return $this->belongsTo('App\AppliedVendor');
    // }

    // function messages()
    // {
    //     return $this->morphMany('App\Message', 'messageable');
    // }

    function VendorApprovals(){

        return $this->hasMany('App\VendorApprovals');
    }

}