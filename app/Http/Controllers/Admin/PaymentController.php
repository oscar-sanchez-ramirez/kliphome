<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function __construct(){
        $this->middleware(['auth','checkadmin']);
    }

    public function index(){
        $general_percent = DB::table('general_stats')->where('title',"percent")->first();
        $payments = DB::table('selected_orders as so')->join('users as u','u.id','so.user_id')->join('fixerman_stats as ft','ft.user_id','u.id')->join('orders as o','o.id','so.order_id')->leftJoin('quotations as q','o.id','q.order_id')->join('payments as p','p.order_id','o.id')
        ->select('p.*','q.workforce','q.price as service_price','ft.percent','u.name','u.lastName')->orderBy('p.id',"DESC")->get();
        return view('admin.payments.index')->with('payments',$payments)->with('general_percent',$general_percent);
    }

    public function percent(){
        $general_percent = DB::table('general_stats')->where('title',"percent")->first();
        return view('admin.payments.percent')->with('general_percent',$general_percent);
    }

    public function update_percent(Request $request){
        switch ($request->options) {
            case 1:
                DB::table('general_stats')->where('title',"percent")->update([
                    'value' => $request->value
                ]);
                DB::table('fixerman_stats')->where('id','>',0)->update([
                    'percent' => $request->value
                ]);
                break;
            case 2:
                DB::table('general_stats')->where('title',"percent")->update([
                    'value' => $request->value
                ]);
                break;
            default:
                # code...
                break;
        }
        return Redirect::action('Admin\PaymentController@index');
    }
}
