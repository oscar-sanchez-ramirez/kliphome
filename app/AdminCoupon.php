<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminCoupon extends Model
{
    public function getUser($id){
        return User::where('id',$id)->first(['name','lastName']);
    }
    public function count($code){
        return Order::where('pre_coupon',$code)->count();
    }
    public function responsable(){
        return $this->belongsTo(User::class, 'responsable','id');
    }
}
