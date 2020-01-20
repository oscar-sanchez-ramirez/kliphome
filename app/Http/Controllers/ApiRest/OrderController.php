<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Order;
use App\User;
use App\Jobs\NotifyNewOrder;
use Illuminate\Support\Facades\Log;
use App\Jobs\ApproveOrderFixerMan;
use App\Notifications\Database\NewQuotation;

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
            if($request->price == "quotation"){
                //Get User and Order
                $order->order_id = $order->id;
                $client = User::where('type',"ADMINISTRATOR")->first();
                $client->notify(new NewQuotation($order));
            }else{
                dispatch(new NotifyNewOrder($order->id));
            }
            return Response(json_encode(array('success' => "La orden de servicio se realizó con éxito")));
        } catch (\Throwable $th) {
            return Response(json_encode(array('failed' => "La orden de servicio no se realizó con éxito")));
        }
    }
    public function testOrder(){
        // dispatch(new NotifyNewOrder(8));
        dispatch(new ApproveOrderFixerMan(78,8));
    }
}
