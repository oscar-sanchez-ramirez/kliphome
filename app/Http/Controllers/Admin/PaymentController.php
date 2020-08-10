<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function __construct(){
        $this->middleware(['auth','checkadmin']);
    }

    public function pagos_fecha(){
        return view('admin.payments.pagos_fecha');
    }

    public function index(Request $request){
        if(\request()->ajax()){
            if($request->filled('chart_query')){
                $payments = DB::table('orders as o')->join('payments as p','p.order_id','o.id')->leftJoin('quotations as q','o.id','q.order_id')->leftJoin('selected_orders as so','o.id','so.order_id')->leftJoin('users as u','u.id','so.user_id')->leftJoin('fixerman_stats as ft','ft.user_id','u.id')
                ->select('p.*','q.workforce','q.price as service_price','ft.percent','u.name','u.lastName')
                ->whereMonth('p.created_at', '>=', intval(substr($request->start,-2,2)))->whereMonth('p.created_at', '<=', intval(substr($request->end,-2,2)))->whereYear('p.created_at','>=',intval(substr($request->start,-7,4)))->whereYear('p.created_at','<=',intval(substr($request->end,-7,4)))
                ->orderBy('p.id',"DESC")->distinct('p.id')->get();
                $stats = $this->stats($payments);
                return $stats;
            }else{
                $payments = Payment::where('order_id',$request->order_id)->get();
                return response()->json([
                    'payments' => $payments
                ]);
            }
        }
        $general_percent = DB::table('general_stats')->where('title',"percent")->first();
        $payments = DB::table('orders as o')->join('payments as p','p.order_id','o.id')->leftJoin('quotations as q','o.id','q.order_id')->leftJoin('selected_orders as so','o.id','so.order_id')->leftJoin('users as u','u.id','so.user_id')->leftJoin('fixerman_stats as ft','ft.user_id','u.id')->select('p.*','q.workforce','q.price as service_price','ft.percent','u.name','u.lastName')->orderBy('p.id',"DESC")->distinct('p.id')->get();
        $stats = $this->stats($payments);
        return view('admin.payments.index',compact('payments','general_percent','stats'));
    }

    private function stats($payments){
        $visita = ['label'=>"Visita + Mano de Obra",'showLine'=>true,'fill'=>false,'borderColor'=>'rgba(230,5,0, 0.3)','data'=>[]];
        $servicio = ['label'=>"Costo por Servicio",'showLine'=>true,'fill'=>false,'borderColor'=>'rgba(0,255,4, 0.9)','data'=>[]];
        for ($i=0; $i < count($payments); $i++) {
            if($payments[$i]->state == 1){
                $date = $this->position($payments[$i]->created_at);
                if($payments[$i]->description == "VISITA"){

                    if(!isset($visita["data"])){
                        array_push($visita["data"],array("x" => $date,"y"=>$payments[$i]->price,'order_id'=>$payments[$i]->order_id));
                    }else{
                        if(array_search($date,array_column($visita["data"],"x"))){
                            $index = array_search($date,array_column($visita["data"],"x"));
                            $visita["data"][$index]["y"] = intval($visita["data"][$index]["y"]) + intval($payments[$i]->price);
                        }else{
                            array_push($visita["data"],array("x" => $date,"y"=>$payments[$i]->price,'order_id'=>$payments[$i]->order_id));
                        }
                    }
                }else if($payments[$i]->description == "PAGO POR SERVICIO"){
                    if(!isset($servicio["data"])){
                        if($payments[$i]->service_price != "0"){
                            array_push($servicio["data"],array("x" => $date,"y"=>$payments[$i]->service_price,'order_id'=>$payments[$i]->order_id));
                        }
                    }else{
                        if(array_search($date,array_column($servicio["data"],"x"))){
                            $index = array_search($date,array_column($servicio["data"],"x"));
                            $servicio["data"][$index]["y"] = intval($servicio["data"][$index]["y"]) + intval($payments[$i]->price);
                        }else{
                            if($payments[$i]->service_price != "0"){
                                array_push($servicio["data"],array("x" => $date,"y"=>$payments[$i]->service_price,'order_id'=>$payments[$i]->order_id));
                            }
                        }
                    }
                    //Acá buscaremos si mano de obra de servicio tiene un pago por visita y sumarlo a ese costo
                    if(array_search($payments[$i]->order_id,array_column($visita["data"],"order_id"))){
                        $index = array_search($payments[$i]->order_id,array_column($visita["data"],"order_id"));
                        $visita["data"][$index]["y"] = intval($visita["data"][$index]["y"]) + intval($payments[$i]->workforce);
                    }else{
                        if($payments[$i]->workforce != "0"){
                            array_push($visita["data"],array("x" => $date,"y"=>$payments[$i]->workforce,'order_id'=>$payments[$i]->order_id));
                        }
                    }
                }
            }
        }
        return array($visita,$servicio);
    }

    private function position($date){
        return $date = substr($date,-19,10);
    }

    public function calcular(Request $request){
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d H:i:i');
        $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d H:i:i');
        $orders = DB::table('orders as o')
        ->join('selected_orders as so','o.id','so.order_id')
        ->join('users as u','so.user_id','u.id')
        ->whereDate('o.service_date','>=',$fecha_inicio)->whereDate('o.service_date','<=',$fecha_fin)
        ->where('so.state',1)
        ->select('so.user_id as fixerman_id','o.id','u.name','u.lastName','u.id as user_id','o.service_date')->orderBy('u.id')
        ->get();
        $ids = array_column($orders->toArray(), 'id');

        $users = [];

        for ($i=0; $i < count($orders); $i++) {
            $stat = DB::table('fixerman_stats')->where('user_id',$orders[$i]->fixerman_id)->first();
            $propinas = Payment::where('description','PROPINA POR SERVICIO')->where('state',1)->where('order_id',$orders[$i]->id)->sum('price');

            $servicios = DB::table('orders as o')
                ->join('payments as p','o.id','p.order_id')
                ->join('quotations as q','q.order_id','o.id')
                ->where('p.description','PAGO POR SERVICIO')->where('p.state',1)->where('o.id',$orders[$i]->id)->sum('q.workforce');
            $servicios = ($servicios * $stat->percent)/100;

            $visita = Payment::where('description','VISITA')->where('state',1)->where('order_id',$orders[$i]->id)->sum('price');
            $visita = ($visita * $stat->percent)/100;

            $user = array("order_id"=>$orders[$i]->id,"user_id"=>$orders[$i]->user_id,"name"=>$orders[$i]->name,'lastName'=>$orders[$i]->lastName,'propinas'=>$propinas,'servicios'=>$servicios,'visita'=>$visita);
            array_push($users,$user);
        }


        for ($j=0; $j < count($users); $j++) {
            if(count($users) == 1){
                continue;
            }else{
                if(count($users) == $j+1){
                    continue;
                }else{
                    if($users[$j]["user_id"] == $users[$j+1]["user_id"]){
                        $users[$j+1]["propinas"] = $users[$j]["propinas"] + $users[$j+1]["propinas"];
                        $users[$j+1]["visita"] = $users[$j]["visita"] + $users[$j+1]["visita"];
                        $users[$j+1]["servicios"] = $users[$j]["servicios"] + $users[$j+1]["servicios"];
                        $users[$j] = "";

                    }else{
                    }
                }
            }
        }
        return response()->json([
            // 'request' => $request->all(),
            'users' => $users,
            'orders' => $orders
        ]);
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
