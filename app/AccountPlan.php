<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountPlan extends Model
{
    public function opportunities(){
        return $this->hasMany('App\Opportunity','accountPlan_id');
    }
}
