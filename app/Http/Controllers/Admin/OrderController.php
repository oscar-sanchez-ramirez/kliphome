<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\SelectedOrders;
use App\Jobs\ApproveOrderFixerMan;
use App\Jobs\DisapproveOrderFixerMan;
use DB;
use Illuminate\Support\Facades\Log;

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
        $fixerman = DB::table('selected_orders as s')->join('orders as o','o.id','s.order_id')->join('users as u','u.id','s.user_id')->select('u.*')->where('o.id',$id)->where('s.state',1)->first();

        return view('admin.orders.orderDetail')->with('orden',$orden)->with('fixerman',$fixerman);
    }
    public function aprobarSolicitudTecnico($fixerman_id,$order_id){
        dispatch(new ApproveOrderFixerMan($fixerman_id,$order_id));
        return back();
    }
    public function eliminarSolicitudTecnico($fixerman_id,$order_id){
        dispatch(new DisapproveOrderFixerMan($fixerman_id,$order_id));
        return back();
    }

}
