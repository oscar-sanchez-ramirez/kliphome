<?php

namespace App\Http\Controllers\ApiRest;

use DB;
use Stripe;
use App\Order;
use App\User;
use App\Coupon;
use App\Quotation;
use Illuminate\Http\Request;
use App\Jobs\NotifyNewOrder;
use App\Jobs\ApproveOrderFixerMan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApiController;
use App\Notifications\Database\NewQuotation;
use App\Notifications\Database\QuotationCancelled;

class OrderController extends ApiController
{
    public function create(Request $request){
        try {
            $order = new Order;
            $order->user_id = $request->user_id;
            $order->selected_id = $request->selected_id;
            $order->type_service = $request->type_service;
            $order->service_date = $request->service_date;
            $order->service_description = $request->service_description;
            $order->service_image = $request->service_image;
            $order->address = $request->address;
            $order->price = $request->price;
            $order->save();
            // if($request->price == "quotation"){
            $order->order_id = $order->id;
            $client = User::where('type',"ADMINISTRATOR")->first();
            $client->notify(new NewQuotation($order));
            // }else{
            //     dispatch(new NotifyNewOrder($order->id));
            // }
            return Response(json_encode(array('success' => "La orden de servicio se realizó con éxito")));
        } catch (\Throwable $th) {
            return Response(json_encode(array('failed' => "La orden de servicio no se realizó con éxito")));
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
        try {
            $price = floatval($request->price);
            Stripe\Stripe::setApiKey("sk_test_brFGYtiWSjTpj5z7y3B8lwsP");
            Stripe\Charge::create ([
                "amount" => $price * 100,
                "currency" => "MXN",
                "source" => $request->stripeToken,
                "description" => "Payment of germanruelas17@gmail.com"
            ]);
            $quotation = Quotation::where('order_id',$request->order_id)->first();
            Order::where('id',$request->order_id)->where('user_id',$request->user_id)->update([
                'price' => $quotation->price
            ]);
            dispatch(new NotifyNewOrder($request->order_id));
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
        Log::notice($request->all());
        $coupon = User::where('code',$request->coupon)->first();
        Log::notice($coupon);
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

    public function testOrder(){
        // dispatch(new NotifyNewOrder(8));
        dispatch(new ApproveOrderFixerMan(78,8));
    }
}
