<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $payments = DB::table('selected_orders as so')->join('orders as o','o.id','so.order_id')->leftJoin('quotations as q','o.id','q.order_id')->join('payments as p','p.order_id','o.id')
        ->select('p.*','q.workforce','q.price as service_price')->orderBy('p.id',"DESC")->get();
        return view('admin.payments.index')->with('payments',$payments);
    }
}
