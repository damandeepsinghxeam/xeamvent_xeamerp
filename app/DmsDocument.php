<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DmsDocument extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = [];

    function category()
    {
        return $this->belongsTo('App\DmsCategory', 'dms_category_id');
    }

    public function keywords()
    {
        return $this->belongsToMany('App\DmsKeyword');
    }

    public function employees()
    {
        return $this->belongsToMany('App\Employee');
    }

    public function departments()
    {
        return $this->belongsToMany('App\Department');
    }
}
