<?php

namespace App\Http\Controllers\Admin;

use DB;
use OneSignal;
use App\User;
use App\Address;
use App\Order;
use App\Payment;
use App\Quotation;
use Carbon\Carbon;
use App\SelectedOrders;
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
        $ordenes = Order::select(['id','user_id','service_description','service_date','state','created_at'])->orderBy('id','DESC')->paginate(10);
        return view('admin.orders.index')->with('ordenes',$ordenes);
    }

    //Show one order
    public function orderDetail(Request $request,$id){
        if($request->filled('notification_id')){
            DB::table('notifications')->where('id',$request->notification_id)->update(['read_at'=>Carbon::now()]);
        }
        $orden = Order::find($id);
        if($orden){
            $fixerman = DB::table('selected_orders as s')->join('orders as o','o.id','s.order_id')->join('users as u','u.id','s.user_id')->select('u.*')->where('o.id',$id)->where('s.state',1)->first();
            $payments = Payment::where('order_id',$id)->get();
            return view('admin.orders.orderDetail')->with('orden',$orden)->with('fixerman',$fixerman)->with('payments',$payments);
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
        // return back()->with('success',"Se envió la cotización");
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
    public function cotizaciones($order_id){
        $quotations = Quotation::where('order_id',$order_id)->get();
        return response()->json([
            'quotations' => $quotations
        ]);
    }
}
