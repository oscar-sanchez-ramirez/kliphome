<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SelectedOrders extends Model
{
    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }
}
