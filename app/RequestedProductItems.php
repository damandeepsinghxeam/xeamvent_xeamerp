<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestedProductItems extends Model
{
    protected $guarded = [];

    function user()
    {
    	return $this->belongsTo('App\User');
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

    function RequestedProductItems(){

        return $this->hasMany('App\VendorApprovals');
    }

    function notifications()
    {
        return $this->morphMany('App\Notification', 'notificationable');
    }

}