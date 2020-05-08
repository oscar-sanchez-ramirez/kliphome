<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminCoupon extends Model
{
    public function getUser($id){
        User::where('id',$id)->first(['name','lastName']);
    }
}
