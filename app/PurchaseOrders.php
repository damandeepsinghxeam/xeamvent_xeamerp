<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrders extends Model
{
    protected $guarded = [];

    function user()
    {
    	return $this->belongsTo('App\User');
    }

    function PurchaseOrderStockItems(){
        return $this->hasMany('App\PurchaseOrderStockItems');
    }

    function PurchaseOrderCoordinators(){
        return $this->hasMany('App\PurchaseOrderCoordinators');
    }

    function PurchaseOrderVendors(){
        return $this->hasMany('App\PurchaseOrderVendors');
    }

    function notifications()
    {
        return $this->morphMany('App\Notification', 'notificationable');
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

}