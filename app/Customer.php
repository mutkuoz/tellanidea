<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    public function strategy(){
        return $this->belongsTo('App\Strategy');
    }

    public function accountPlans(){
        return $this->hasMany('App\AccountPlan');
    }

    public function opportunities()
    {
        return $this->hasManyThrough('App\Opportunity', 'App\AccountPlan','customer_id' , 'accountPlan_id');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}
