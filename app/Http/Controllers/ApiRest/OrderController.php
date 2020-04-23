<?php

namespace App\Http\Controllers\ApiRest;

use DB;
use Stripe;
use App\Order;
use App\User;
use App\Coupon;
use App\Quotation;
use App\Payment;
use Illuminate\Http\Request;
use App\Jobs\NotifyNewOrder;
use App\Jobs\Mail\MailOrderAccepted;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApiController;
use App\Notifications\Database\QuotationCancelled;

class OrderController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function create(Request $request){
        try {

            if($request->visit_price == "quotation"){
                $order = new Order;
                $order->user_id = $request->user_id;
                $order->selected_id = $request->selected_id;
                $order->type_service = $request->type_service;
                $order->service_date = $request->service_date;
                $order->service_description = $request->service_description;
                $order->service_image = $request->service_image;
                $order->address = $request->address;
                $order->price = 'quotation';
                $order->visit_price = $request->visit_price;
                $order->save();
                $user = $request->user();
                dispatch(new NotifyNewOrder($order->id,$user->email));
            }else{
                $price = 'quotation';
                $price = floatval($request->price);
                try {
                    Stripe\Stripe::setApiKey("sk_test_f2VYH7q0KzFbrTeZfSvSsE8R00VBDQGTPN");
                    if(substr($request->token,0,3) == "cus"){
                        $pago = Stripe\Charge::create ([
                            "amount" => $request->visit_price * 100,
                            "currency" => "MXN",
                            "customer" => $request->token,
                            "description" => "Pago por visita"
                        ]);
                    }else{
                        $pago = Stripe\Charge::create ([
                            "amount" => $request->visit_price * 100,
                            "currency" => "MXN",
                            "source" => $request->token,
                            "description" => "Pago por visita"
                        ]);
                    }


                    $order = new Order;
                    $order->user_id = $request->user_id;
                    $order->selected_id = $request->selected_id;
                    $order->type_service = $request->type_service;
                    $order->service_date = $request->service_date;
                    $order->service_description = $request->service_description;
                    $order->service_image = $request->service_image;
                    $order->address = $request->address;
                    $order->price = 'quotation';
                    $order->visit_price = $request->visit_price;
                    $order->save();
                    $order->order_id = $order->id;

                    $payment = new Payment;
                    $payment->order_id = $order->id;
                    $payment->code_payment = $pago->id;
                    $payment->description = "VISITA";
                    $payment->state = true;
                    $payment->price = $request->visit_price;
                    $payment->save();
                    $user = $request->user();
                    dispatch(new NotifyNewOrder($order->id,$user->email));
                    return response()->json([
                        'success' => true,
                        'message' => "La orden de servicio se realizó con éxito"
                    ]);
                } catch (\Throwable $th) {
                    $payment = new Payment;
                    $payment->order_id = $order->id;
                    $payment->description = "VISITA";
                    $payment->state = false;
                    $payment->price = $request->visit_price;
                    $payment->save();
                    return response()->json([
                        'success' => false
                    ]);
                }
            }

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "La orden de servicio no se realizó"
            ]);
        }
    }

    public function suspend(Request $request){
        Order::where('id',$request->order_id)->where('user_id',$request->user_id)->update([
            'price' => "CANCELLED",
            'state' => "CANCELLED"
        ]);
        $order = Order::where('id',$request->order_id)->first();
        $client = User::where('type',"ADMINISTRATOR")->first();
        $client->notify(new QuotationCancelled($order));

    }

    public function approve(Request $request){
        // L
        try {
            $price = floatval($request->price);
            try {
                Stripe\Stripe::setApiKey("sk_test_f2VYH7q0KzFbrTeZfSvSsE8R00VBDQGTPN");
                if(substr($request->stripeToken,0,3) == "cus"){
                    $pago = Stripe\Charge::create ([
                        "amount" => $price * 100,
                        "currency" => "MXN",
                        "customer" => $request->stripeToken,
                        "description" => "Payment of order ".$request->order_id
                    ]);
                }else{
                    $pago = Stripe\Charge::create ([
                        "amount" => $price * 100,
                        "currency" => "MXN",
                        "source" => $request->stripeToken,
                        "description" => "Payment of order ".$request->order_id
                    ]);
                }
                $payment = new Payment;
                $payment->order_id = $request->order_id;
                $payment->description = "PAGO POR SERVICIO";
                $payment->code_payment = $pago->id;
                $payment->state = true;
                $payment->price = $price;
                $payment->save();
            } catch (\Throwable $th) {
                $payment = new Payment;
                $payment->order_id = $request->order_id;
                $payment->description = "PAGO POR SERVICIO";
                $payment->state = false;
                $payment->price = $price;
                $payment->save();
                return response()->json([
                    'success' => false
                ]);
            }

            $quotation = Quotation::where('order_id',$request->order_id)->first();
            Order::where('id',$request->order_id)->where('user_id',$request->user_id)->update([
                'price' => $quotation->price
            ]);
            if($request->coupon != ""){
                $coupon = new Coupon;
                $coupon->code = $request->coupon;
                $coupon->user_id = $request->user_id;
                $coupon->save();
            }
            dispatch(new MailOrderAccepted($request->order_id));
            return response()->json([
                'success' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function coupon(Request $request){
        $user = User::where('id',$request->user_id)->first();
        $coupon = User::where('code',$request->coupon)->where('code','!=',$user->code)->first();
        if(empty($coupon)){
            return response()->json([
                'success' => false,
                'message' => "Cupón no encontrado"
            ]);
        }

        $valid = Coupon::where('user_id',$request->user_id)->first();
        if(!empty($valid)){
            return response()->json([
                'success' => false,
                'message' => "Ya usaste un cupón de primera orden"
            ]);
        }
        if(!empty($coupon) && empty($valid)){
            return response()->json([
                'success' => true,
                'message' => "Cupón válido"
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "No se encontraron datos"
            ]);
        }
    }
}
