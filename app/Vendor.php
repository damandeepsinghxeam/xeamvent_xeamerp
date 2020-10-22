<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    // protected $fillable = ['name','color','company'];
    protected $guarded = [];
    // protected $table = 'vendors';

    
    public function country() {
        return $this->belongsTo('App\Country', 'country_id', 'id');
    }

    public function state() {
        return $this->belongsTo('App\State', 'state_id', 'id');
    }

    public function city() {
        return $this->belongsTo('App\City', 'city_id', 'id');
    }

    function notifications()
    {
        return $this->morphMany('App\Notification', 'notificationable');
    }
}