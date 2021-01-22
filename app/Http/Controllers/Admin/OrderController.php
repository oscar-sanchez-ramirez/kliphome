<?php

namespace App\Http\Controllers\Admin;

use DB;
use OneSignal;
use App\User;
use App\Order;
use App\Address;
use App\Payment;
use App\ExtraInfo;
use App\Quotation;
use App\Qualify;
use App\Category;
use App\FixermanStat;
use Carbon\Carbon;
use App\AdminCoupon;
use App\SelectedOrders;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use App\Jobs\ApproveOrderFixerMan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\DisapproveOrderFixerMan;
use App\Notifications\Database\CancelOrder;
use App\Notifications\Database\FinishedOrder;
use App\Notifications\Database\QuotationSended;

class OrderController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //Show all orders
    public function index(Request $request){
        if(\request()->ajax()){
            if($request->chart_query == "all"){
                $ordenes = $this->cast_date(DB::table('orders')->select(DB::raw('count(id) as total'),DB::raw('date(created_at) as dates'))
                ->where('state','!=','CANCELLED')->groupBy('dates')->orderBy('dates','asc')->get());
                return $ordenes;
            }elseif($request->chart_query == "filtro"){
                $ordenes = $this->filtro($request->key);
                return $ordenes;
            }else{
                $ordenes = $this->cast_date(DB::table('orders')->select(DB::raw('count(id) as total'),DB::raw('date(created_at) as dates'))
                ->whereBetween(DB::raw('DATE(created_at)'), array($request->start, $request->end))
                ->where('state','!=','CANCELLED')->groupBy('dates')->orderBy('dates','asc')->get());
                return $ordenes;
            }
        }

        if($request->filled('filtro')){
            $ordenes = $this->filtro($request->filtro);
        }else{
            if($request->filled('usuario')){
                $ordenes = Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('user_id',$request->usuario)->orderBy('id','DESC')->get();
            }else{
                $ordenes = Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->orderBy('id','DESC')->paginate(10);
            }
        }
        return view('admin.orders.index')->with('ordenes',$ordenes);
    }

    private function cast_date($users){
        $months = [];
        for ($i=0; $i < count($users); $i++) {
            $date = substr($users[$i]->dates,-16,10);
            if(array_search($date,array_column($months,"x"))){
                $index = array_search($date,array_column($months,"x"));
                $months[$index]["y"] = $months[$index]["y"] + $users[$i]->total;
            }else{
                array_push($months,array("x" => $date,"y"=>$users[$i]->total));
            }
        }
        return $months;
    }

    //Show one order
    public function orderDetail(Request $request,$id){
        if($request->filled('notification_id')){
            DB::table('notifications')->where('id',$request->notification_id)->update(['read_at'=>Carbon::now()]);
        }
        $orden = Order::where('id',$id)->with('gallery','fixerman_user')->first();
        if($orden){
            $fixerman = DB::table('selected_orders as s')->join('orders as o','o.id','s.order_id')->join('users as u','u.id','s.user_id')->select('u.*')->where('o.id',$id)->where('s.state',1)->first();
            $payments = Payment::where('order_id',$id)->get();
            $extra_info = ExtraInfo::where('order_id',$id)->first();
            return view('admin.orders.orderDetail')->with('orden',$orden)->with('fixerman',$fixerman)->with('payments',$payments)->with('extra_info',$extra_info);
        }else{
            return back();
        }
    }

    public function detalle_usuario($id,$address_id){
        $user = User::where('id',$id)->first();
        $address = Address::where('id',$address_id)->first();
        return response()->json([
            'user' => $user,
            'address' => $address
        ]);
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
        $check_quotations = Quotation::where('order_id',$order_id)->count();
        if($check_quotations == 0){
            Order::where('id',$order_id)->update([ 'price' => "waitquotation"]);
        }
        $quotation = new Quotation;
        $quotation->order_id = $order_id;
        $quotation->price = $request->price;
        $quotation->solution = $request->solution;
        $quotation->materials = $request->materials;
        $quotation->workforce = $request->workforce;
        $quotation->warranty_num = $request->warranty_num;
        $quotation->warranty_text = $request->warranty_text;
        $quotation->state = 0;
        $quotation->save();


        $date = \Carbon\Carbon::createFromFormat('Y/m/d H:i', $order->service_date);
        $date = $date->format('d-M-Y H:i');

        $quotation->mensajeClient = "Recibiste la cotización de tu orden para el ".$date;
        $quotation->visit_price = $order->visit_price;
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
        return response()->json([
            'success' => true,
            'message' => "Se envió la cotización"
        ]);
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

    public function cancelarCotizacion($id){
        Quotation::where('id',$id)->update([
            'state' => 2
        ]);
        return response()->json([
            'success' => true,
            'message' => "Se canceló la cotización"
        ]);
    }

    public function confimarCotizacion(Request $request,$id){
        Quotation::where('id',$id)->update([
            'state' => 1
        ]);
        $verificar_pagos = Payment::where('order_id',$request->order_id)->get();
        if($verificar_pagos->isEmpty()){
            $payment = new Payment;
            $payment->order_id = $request->order_id;
            $payment->code_payment = "EFECTIVO";
            $payment->description = "PAGO POR SERVICIO";
            $payment->state = true;
            $payment->price = intval($request->price) + intval($request->workforce);
            $payment->save();
        }
        return response()->json([
            'success' => true,
            'message' => "Se validó la cotización"
        ]);
    }

    public function cancelOrder($id){
        Order::where('id',$id)->update([
            'state' => 'CANCELLED'
        ]);
        $order = Order::where('id',$id)->first();
        $order["mensajeClient"] = "Tu orden de servicio ha sido cancelada";
        $user = User::where('id',$order->user_id)->first();
        $user->notify(new CancelOrder($order,$user->email));
    }

    // public function qualifies($id){
    //     $qualifies = DB::table('orders as o')->join('selected_orders as so','so.order_id','o.id')->join('qualifies as q','q.selected_order_id','so.id')->select('q.*')->where('o.id',$id)->get();
    //     // Qualify::where('selected_order_id')->get();
    //     return response()->json([
    //         'qualifies' => $qualifies
    //       ]);
    // }

    public function markDone($id){
        $order_id = $id;
        //Get User and Order
        $order = Order::where('id',$order_id)->first();
        //Notify
        $order["mensajeClient"] = "¡Gracias por usar KlipHome! Tu servicio ha terminado, ¡Califícalo ahora! ";
        $client = User::where('id',$order->user_id)->first();
        $client->notify(new FinishedOrder($order));
        //Onesignal Notification
        $type = "App\Notifications\Database\FinishedOrder";
        $content = $order;
        OneSignal::sendNotificationUsingTags(
            "KlipHome ha marcardo el servicio como terminado. ¡Valóralo ahora!",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $client->email],
            ),
            $type,
            $content,
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );

        //Update order
        Order::where('id',$order_id)->update([
            'finished_at' => Carbon::now(),
            'state' => 'FIXERMAN_DONE'
        ]);
        $check_fixerman = DB::table('orders as o')->join('selected_orders as so','o.id','so.order_id')->join('users as u','u.id','so.user_id')->where('so.state',1)->get();
        if($check_fixerman[0]->id != ""){
            $fixerman = User::where('id',$check_fixerman[0]->id)->first();
            FixermanStat::where('user_id',$check_fixerman[0]->id)->increment('completed');
        }
    }

    public function check(){
        $hoy = Carbon::now()->format('Y/m/d');
        $orders = DB::table('selected_orders as s')->join('orders as o','o.id','s.order_id')->join('users as u','s.user_id','u.id')
        ->select('o.id','u.id as id_user','o.service_date')
        ->whereDate('o.service_date',$hoy)
        ->get();

        return $orders;
    }

    public function cupon($coupon_code){
        $coupon = User::where('code',$coupon_code)->first();
        if(!empty($coupon)){
            $coupon->discount = 5;
            return response()->json([
                'coupon' => $coupon
            ]);
        }else{
            $admin_coupon = AdminCoupon::where('code',$coupon_code)->first();
            return response()->json([
                'coupon' => $admin_coupon
            ]);
        }
    }

    public function cotizaciones($order_id){
        $quotations = Quotation::where('order_id',$order_id)->get();
        return response()->json([
            'quotations' => $quotations
        ]);
    }

    public function nueva_orden(){
        $categories = Category::all();
        return view('admin.orders.create')->with('categories',$categories);
    }

    public function store(Request $request){
        try {
            if($request->filled('service_image')){ $image = $request->service_image;}else{$image = "https://kliphome.com/images/default.jpg";}
            $order = new Order;
            $order->user_id = $request->user_id;
            $order->selected_id = $request->selected_id;
            $order->type_service = $request->type_service;
            $order->service_date = $request->service_date;
            $order->service_description = $request->service_description;
            $order->service_image = $image;
            $order->address = $request->address;
            $order->price = 'quotation';
            $order->visit_price = $request->visit_price;
            $order->pre_coupon = $request->coupon;
            $order->save();
            return response()->json([
                'success' => true,
                'order' => $order
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function nuevo_pago(Request $request,$id){
        $payment = new Payment;
        $payment->order_id = $id;
        $payment->code_payment = $request->code_payment;
        $payment->description = $request->description;
        $payment->state = true;
        $payment->price = $request->price;
        $payment->save();
        return response()->json([
            'success' => true,
            'payment' => $payment
        ]);
    }

    public function busqueda(Request $request){
        $key = $request->keywords;
        $orders = DB::table('users as u')->join('orders as o','o.user_id','u.id')
        ->where('u.name','LIKE','%'.$key.'%')->orWhere('u.lastName','LIKE','%'.$key.'%')->where('u.state',1)->get();

        foreach ($orders as $key => $order) {
            $order->categoria = $this->getCategory($order->type_service,$order->selected_id);
            $order->stateicon = $this->getState($order);
        }
        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }
    private function filtro($key){
        switch ($key) {
            case 'con_tecnico':
                $fixerman = SelectedOrders::where('state',1)->pluck('order_id');
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereIn('id',$fixerman)->orderBy('id','DESC')->get();
            case 'tecnico_llego':
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('fixerman_arrive',"SI")->orderBy('id','DESC')->get();
            case 'contizacion_pendiente':
                $quotations = Quotation::where('state',0)->pluck('order_id');
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereIn('id',$quotations)->orderBy('id','DESC')->get();
            case 'cotizacion_pagada':
                $quotations = Quotation::where('state',1)->pluck('order_id');
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereIn('id',$quotations)->orderBy('id','DESC')->get();
            case 'sin_cotizacion':
                $quotations = Quotation::where('state',1)->pluck('order_id');
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereNotIn('id',$quotations)->orderBy('id','DESC')->get();
            case 'terminados':
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('state',"FIXERMAN_DONE")->orderBy('id','DESC')->get();
            case 'cancelados':
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('state',"CANCELLED")->orderBy('id','DESC')->get();
            case 'calificados':
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('state',"QUALIFIED")->orderBy('id','DESC')->get();
            case 'todos':
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->orderBy('id','DESC')->paginate(10);
            default:
                # code...
                break;
        }
    }

    public function getService($type,$id){
        switch ($type) {
            case 'Category':
                $category = DB::table('categories')->select('title as category')->where('id',$id)->get();
                return $category[0]->category;
                break;
            case 'SubCategory':
                $category  = DB::table('sub_categories as su')->join('categories as ca','su.category_id','ca.id')->select('ca.title as category','su.title as sub_category')->where('su.id',$id)->get();
                return $category[0]->category.'/'.$category[0]->sub_category;
                break;
            case 'Service':
                $category = DB::table('services as se')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('se.title as service','ca.title as category','su.title as sub_category')->where('se.id',$id)->get();
                return $category[0]->category.'/'.$category[0]->sub_category.'/'.$category[0]->service;
                break;
            case 'SubService':
                $category = DB::table('sub_services as subse')->join('services as se','se.id','subse.service_id')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title as category','su.title as sub_category','subse.title as service','se.title as serviceTrait')->where('subse.id',$id)->get();
                return $category[0]->category.'/'.$category[0]->sub_category.'/'.$category[0]->serviceTrait.'/'.$category[0]->service;
                break;
            default:
                # code...
                break;
        }
    }

    public function getCategory($type,$id){
        switch ($type) {
            case 'Category':
                $category = DB::table('categories')->select('title as category')->where('id',$id)->get();
                return $category[0]->category;
                break;
            case 'SubCategory':
                $category  = DB::table('sub_categories as su')->join('categories as ca','su.category_id','ca.id')->select('ca.title as category','su.title as sub_category')->where('su.id',$id)->get();
                return $category[0]->category;
                break;
            case 'Service':
                $category = DB::table('services as se')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('se.title as service','ca.title as category','su.title as sub_category')->where('se.id',$id)->get();
                return $category[0]->category;
                break;
            case 'SubService':
                $category = DB::table('sub_services as subse')->join('services as se','se.id','subse.service_id')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title as category','su.title as sub_category','subse.title as service','se.title as serviceTrait')->where('subse.id',$id)->get();
                return $category[0]->category;
                break;
            default:
                # code...
                break;
        }
    }

    public function export(Request $request){
        if($request->filtro == ''){
            return back();
        }
        return Excel::download(new OrdersExport($request->filtro), 'ordenes.xlsx');
    }

    public function getState($order){
        $fixerman = SelectedOrders::where('order_id',$order->id)->where('state',1)->first();
        if($fixerman){$fixerman = "success";}else{$fixerman = "secondary";}
        $quotation = Quotation::where('order_id',$order->id)->orderBy('id','DESC')->first();
        if($quotation){if($quotation->state == 0){$quotation = "warning";}else if($quotation->state == 1){$quotation = "success";}else if($quotation->state == 2){$quotation = "danger";}}else{$quotation = "secondary";}
        if($order->fixerman_arrive === 'SI'){$arrive = '<i class="zmdi zmdi-badge-check" id="success"></i>';}else{$arrive = '<i class="zmdi zmdi-badge-check" id="secondary"></i>';}
        if($order->state === 'FIXERMAN_DONE' || $order->state === 'QUALIFIED'){$done = '<i class="zmdi zmdi-badge-check" id="success"></i>';}else{$done = '<i class="zmdi zmdi-badge-check" id="secondary"></i>';}
        if($order->state === 'QUALIFIED'){$qualify = '<i class="zmdi zmdi-badge-check" id="success"></i>';}else{$qualify = '<i class="zmdi zmdi-badge-check" id="secondary"></i>';}

        return '<i class="zmdi zmdi-badge-check" id="'.$fixerman.'"></i>&nbsp;'.$arrive.'&nbsp;'.'<i class="zmdi zmdi-badge-check" id="'.$quotation.'"></i>&nbsp;'.$done.'&nbsp;'.$qualify;
    }
}
