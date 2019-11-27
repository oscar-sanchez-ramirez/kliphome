<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function clientName($id){
        return User::where('id',$id)->first(['name','lastName','email','phone']);
    }
    public function clientAddress($id){
        return Address::where('id',$id)->first(['alias','address']);
    }
}
