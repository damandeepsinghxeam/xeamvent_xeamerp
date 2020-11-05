<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderCoordinators extends Model
{
    protected $guarded = [];

    function user()
    {
    	return $this->belongsTo('App\User');
    }

    function notifications()
    {
        return $this->morphMany('App\Notification', 'notificationable');
    }

}