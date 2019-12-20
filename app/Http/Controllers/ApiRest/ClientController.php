<?php

namespace App\Http\Controllers\ApiRest;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use DB;

class ClientController extends ApiController
{
    public function historyOrders($id){
        $orders = DB::table('orders as o')
        ->join('addresses as a','o.address','a.id')
        ->leftJoin('selected_orders as so','o.id','so.order_id')
        // ->join('users as u','u.id','so.user_id')
        // ,'u.name','u.lastName'
        ->select('o.*','a.alias')->where('o.user_id',$id)->get();
        return Response(json_encode(array('orders' => $orders)));
    }
}
