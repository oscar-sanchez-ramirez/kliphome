<?php

namespace App\Http\Controllers\Admin;

use DB;
use OneSignal;
use App\User;
use App\Order;
use App\Quotation;
use App\SelectedOrders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\ApproveOrderFixerMan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Jobs\DisapproveOrderFixerMan;
use App\Notifications\Database\QuotationSended;

class OrderController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //Show all orders
    public function index(){
        $ordenes = Order::select(['id','user_id','service_description','service_date','created_at'])->orderBy('id','DESC')->paginate(10);
        return view('admin.orders.index')->with('ordenes',$ordenes);
    }

    //Show one order
    public function orderDetail(Request $request,$id){
        if($request->filled('notification_id')){
            DB::table('notifications')->where('id',$request->notification_id)->update(['read_at'=>Carbon::now()]);
        }
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

    public function enviarCotizacion(Request $request,$order_id){

        $order = Order::where('id',$order_id)->first();
        $user = User::where('id',$order->user_id)->first();
        $quotation = new Quotation;
        $quotation->order_id = $order_id;
        $quotation->price = $request->price;
        $quotation->solution = $request->solution;
        $quotation->materials = $request->materials;
        $quotation->save();


        $date = \Carbon\Carbon::createFromFormat('Y/m/d H:i', $order->service_date);
        $date = $date->format('d-M-Y H:i');

        $quotation->mensajeClient = "Recibiste la cotización de tu orden para el ".$date;
        $user->notify(new QuotationSended($quotation));

        $type = "App\Notifications\Database\QuotationSended";
        $content = $quotation;
        OneSignal::sendNotificationUsingTags(
            "Acabas de recibir una cotización",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $user->email],
            ),
            $type,
            $content,
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
        Order::where('id',$order_id)->update([
            'price' => "waitquotation"
        ]);
        return back()->with('success',"Se envió la cotización");
    }

    public function notify($order_id){
        $order = Order::where('id',$order_id)->first();
        $user = User::where('id',$order->user_id)->first();
        OneSignal::sendNotificationUsingTags(
            "Estamos realizando tu cotización, en breve la recibirás",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $user->email],
            ),
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
        return back()->with('success',"Se notifico al cliente");
    }

}
