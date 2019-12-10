<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Order;
use App\Jobs\NotifyNewOrder;
use Illuminate\Support\Facades\Log;

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
            $order->save();
            Log::notice($order);
            dispatch(new NotifyNewOrder($order->id));
            return Response(json_encode(array('success' => "La orden de servicio se realizó con éxito")));
        } catch (\Throwable $th) {
            return Response(json_encode(array('failed' => "La orden de servicio no se realizó con éxito")));
        }
    }
}
