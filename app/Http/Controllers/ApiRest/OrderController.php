<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\Log;

class OrderController extends ApiController
{
    public function create(Request $request){
        // Log::info('inicio create');
        // try {
            // $order = new Order;
            // $order->user_id = $request->user_id;
            // $order->selected_id = $request->selected_id;
            // $order->type_service = $request->type_service;
            // $order->service_date = $request->service_date;
            // $order->service_description = $request->service_description;
            // $order->service_image = $request->service_image;
            // $order->address = $request->address;
            // $order->save();
            $order = new Order;
            $order->user_id = 14;
            $order->selected_id = 1;
            $order->type_service = "SubService";
            $order->service_date = "13:13";
            $order->service_description = "Bvccc";
            $order->service_image = "https://firebasestorage.googleapis.com/v0/b/kliphome-c529b.appspot.com/o/images%2F1574957605133?alt=media&token=59a46fd3-6d12-4c5b-b0a4-e9f4b4f5921c";
            $order->address = 6;
            $order->save();
            // Log::info('llego');
            return Response(json_encode(array('success' => "La orden de servicio se realizó con éxito")));
        // } catch (\Throwable $th) {
        //     Log::info('fail');
        //     return Response(json_encode(array('failed' => "La orden de servicio no se realizó con éxito")));
        // }
    }
}
