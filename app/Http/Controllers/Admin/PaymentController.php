<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function __construct(){
        $this->middleware(['auth','checkadmin']);
    }

    public function index(){
        $general_percent = DB::table('general_stats')->where('title',"percent")->first();
        $payments = DB::table('selected_orders as so')->join('orders as o','o.id','so.order_id')->leftJoin('quotations as q','o.id','q.order_id')->join('payments as p','p.order_id','o.id')
        ->select('p.*','q.workforce','q.price as service_price')->orderBy('p.id',"DESC")->get();
        return view('admin.payments.index')->with('payments',$payments)->with('general_percent',$general_percent);
    }

    public function percent(){
        $general_percent = DB::table('general_stats')->where('title',"percent")->first();
        return view('admin.payments.percent')->with('general_percent',$general_percent);
    }
}
