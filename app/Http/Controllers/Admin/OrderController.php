<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //Show all orders
    public function index(){
        $ordenes = Order::all(['id','user_id','service_description','service_date','created_at']);
        return view('admin.orders.index')->with('ordenes',$ordenes);
    }
    //Show one order
    public function orderDetail($id){
        $orden = Order::find($id);
        return view('admin.orders.orderDetail')->with('orden',$orden);
    }

}
