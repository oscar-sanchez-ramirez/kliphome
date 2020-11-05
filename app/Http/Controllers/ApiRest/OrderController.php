<?php

namespace App\Http\Controllers\ApiRest;

use DB;
use Stripe;
use App\Cita;
use App\User;
use App\Order;
use App\Coupon;
use App\Payment;
use App\Quotation;
use App\ExtraInfo;
use App\TempPayment;
use App\AdminCoupon;
use App\OrderGallery;
use App\ConfigSystem;
use Illuminate\Http\Request;
use App\Jobs\NotifyNewOrder;
use Illuminate\Support\Facades\Log;
use App\Jobs\Mail\MailOrderAccepted;
Use App\Jobs\Mail\ConektaError;
use App\Http\Controllers\ApiController;
use App\Notifications\Database\newDate;
use App\Notifications\Database\OrderCancelled;
use App\Notifications\Database\QuotationCancelled;

class OrderController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function create(Request $request){
        Log::notice("NUEVA ORDEN");
        Log::notice($request->all());
        $user = $request->user();
        // if($user->email == "germanruelas17@gmail.com" || $user->email == "adrimabarak@hotmail.com"){
            if($request->visit_price == "quotation"){
                if($request->filled('service_image')){ $image = $request->service_image;}else{$image = "https://kliphome.com/images/default.jpg";}
                //No necesita pago de visita (Telefono, Computadora)
                $order = new Order;
                $order->user_id = $user->id;
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
                if($user->email != "germanruelas17@gmail.com"){
                    dispatch(new NotifyNewOrder($order->id,$user->email));
                }
                return response()->json([
                    'success' => true,
                    'message' => "La orden de servicio se realizó con éxito",
                    'order' => $order
                ]);
            }else{
                $tipo_de_pago = ConfigSystem::payment;
                if($tipo_de_pago["conekta"] == true){
                    \Conekta\Conekta::setApiKey(ConfigSystem::conekta_key);
                    try{
                        $price = floatval($request->visit_price);
                        if($request->filled('service_image')){ $image = $request->service_image;}else{$image = "https://kliphome.com/images/default.jpg";}
                        if(substr($request->token,0,3) == "tok"){
                            $pago = \Conekta\Order::create(
                            [
                                "line_items" => [["name" => "PAGO POR VISITA","unit_price" => $price * 100,"quantity" => 1]],
                                "currency" => "MXN",
                                "customer_info" => ["name" => $user->name.' '.$user->lastName,"email" => $user->email,"phone" => $user->phone],
                                "charges" => [["payment_method" => ["type" => "card","token_id" => $request->token]]
                                ]
                            ]
                            );
                            if($pago->payment_status == "paid"){
                                $order = $this->guardar_orden($request,$user->id,$image);
                                $this->guardar_pago($order->id,$pago->id,$request->visit_price,"VISITA");
                            }else{
                                return response()->json([
                                    'success' => false
                                ]);
                            }
                        }else if(substr($request->token,0,3) == "cus"){
                            if($user->email == 'germanruelas17@gmail.com'){
                                $pago = \Conekta\Order::create([
                                    "line_items" => [["name" => "PAGO POR VISITA","unit_price" => $price * 100,"quantity" => 1]],
                                    'currency' => 'MXN',
                                    'customer_info' => ['customer_id' => $request->token],
                                    'charges' => [['payment_method' => ['type' => 'default']]]
                                  ]);
                                $order = $this->guardar_orden($request,$user->id,$image);
                                $this->guardar_pago($order->id,"PRUEBA",$request->visit_price,"VISITA");
                            }else{
                                $pago = \Conekta\Order::create([
                                    "line_items" => [["name" => "PAGO POR VISITA","unit_price" => $price * 100,"quantity" => 1]],
                                    'currency' => 'MXN',
                                    'customer_info' => ['customer_id' => $request->token],
                                    'charges' => [['payment_method' => ['type' => 'default']]]
                                  ]);
                                  if($pago->payment_status == "paid"){
                                        $order = $this->guardar_orden($request,$user->id,$image);
                                        $this->guardar_pago($order->id,$pago->id,$request->visit_price,"VISITA");
                                    }else{
                                        return response()->json([
                                            'success' => false
                                        ]);
                                    }
                            }

                        }else if($request->token == "temp"){
                            $temp = TempPayment::where('user_id',$user->id)->where('price',$request->visit_price)->first();
                            $order = $this->guardar_orden($request,$user->id,$image);
                            $this->guardar_pago($order->id,$temp->code_payment,$request->visit_price,"VISITA");
                            $temp->delete();
                        }
                        if($user->email != "adrimabarak@hotmail.com" && $user->email != "germanruelas17@gmail.com"){
                            dispatch(new NotifyNewOrder($order->id,$user->email));
                        }
                        return response()->json([
                            'success' => true,
                            'message' => "La orden de servicio se realizó con éxito",
                            'order' => $order
                        ]);

                    } catch (\Conekta\ProcessingError $error){
                        Log::error($error);
                        $name = $user->name.' '.$user->lastName;
                        dispatch(new ConektaError($name,'PAGO POR VISITA',$error->getMessage()));
                        return response()->json([
                            'success' => false,
                            'message' => $error->getMessage()
                        ]);
                    } catch (\Conekta\ParameterValidationError $error){
                        Log::error($error);
                        $name = $user->name.' '.$user->lastName;
                        dispatch(new ConektaError($name,'PAGO POR VISITA',$error->getMessage()));
                        return response()->json([
                            'success' => false,
                            'message' => $error->getMessage()
                        ]);
                    } catch (\Conekta\Handler $error){
                        Log::error($error);
                        $name = $user->name.' '.$user->lastName;
                        dispatch(new ConektaError($name,'PAGO POR VISITA',$error->getMessage()));
                        return response()->json([
                            'success' => false,
                            'message' => $error->getMessage()
                        ]);
                    } catch (\Throwable $th) {
                        Log::error($th);
                        return response()->json([
                            'success' => false,
                            'message' => "Hubo un inconveniente en tu pago"
                        ]);
                    }
                }else{
                    try {
                        if($request->filled('service_image')){ $image = $request->service_image;}else{$image = "https://kliphome.com/images/default.jpg";}
                        $price = floatval($request->price);
                        Stripe\Stripe::setApiKey("sk_live_cgLVMsCuyCsluw3Tznx1RuPS00UJQp8Rqf");
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
                        if($pago->paid == true){
                            $order = new Order;
                            $order->user_id = $user->id;
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
                            $order->order_id = $order->id;

                            $payment = new Payment;
                            $payment->order_id = $order->id;
                            $payment->code_payment = $pago->id;
                            $payment->description = "VISITA";
                            $payment->state = true;
                            $payment->price = $request->visit_price;
                            $payment->save();
                            // $user = $request->user();
                            if($user->email != "adrimabarak@hotmail.com"){

                                dispatch(new NotifyNewOrder($order->id,$user->email));
                            }
                            return response()->json([
                                'success' => true,
                                'message' => "La orden de servicio se realizó con éxito",
                                'order' => $order
                            ]);
                        }else{
                            return response()->json([
                                'success' => false
                            ]);
                        }
                    } catch (\Throwable $th) {
                        Log::error($th);
                        return response()->json([
                            'success' => false,
                            'message' => "La orden de servicio no se realizó"
                        ]);
                    }
                }
            }
        // }
        // else{
        //     try {
        //         if($request->filled('service_image')){ $image = $request->service_image;}else{$image = "https://kliphome.com/images/default.jpg";}
        //         if($request->visit_price == "quotation"){
        //             //No necesita pago de visita (Telefono, Computadora)

        //             $order = new Order;
        //             $order->user_id = $user->id;
        //             $order->selected_id = $request->selected_id;
        //             $order->type_service = $request->type_service;
        //             $order->service_date = $request->service_date;
        //             $order->service_description = $request->service_description;
        //             $order->service_image = $image;
        //             $order->address = $request->address;
        //             $order->price = 'quotation';
        //             $order->visit_price = $request->visit_price;
        //             $order->pre_coupon = $request->coupon;
        //             $order->save();
        //             dispatch(new NotifyNewOrder($order->id,$user->email));
        //             return response()->json([
        //                 'success' => true,
        //                 'message' => "La orden de servicio se realizó con éxito",
        //                 'order' => $order
        //             ]);
        //         }else{
        //             $price = floatval($request->price);
        //             Stripe\Stripe::setApiKey("sk_live_cgLVMsCuyCsluw3Tznx1RuPS00UJQp8Rqf");
        //             if(substr($request->token,0,3) == "cus"){
        //                 $pago = Stripe\Charge::create ([
        //                     "amount" => $request->visit_price * 100,
        //                     "currency" => "MXN",
        //                     "customer" => $request->token,
        //                     "description" => "Pago por visita"
        //                 ]);
        //             }else{
        //                 $pago = Stripe\Charge::create ([
        //                     "amount" => $request->visit_price * 100,
        //                     "currency" => "MXN",
        //                     "source" => $request->token,
        //                     "description" => "Pago por visita"
        //                 ]);
        //             }
        //             Log::notice($request->all());
        //             if($pago->paid == true){
        //                 $order = new Order;
        //                 $order->user_id = $user->id;
        //                 $order->selected_id = $request->selected_id;
        //                 $order->type_service = $request->type_service;
        //                 $order->service_date = $request->service_date;
        //                 $order->service_description = $request->service_description;
        //                 $order->service_image = $image;
        //                 $order->address = $request->address;
        //                 $order->price = 'quotation';
        //                 $order->visit_price = $request->visit_price;
        //                 $order->pre_coupon = $request->coupon;
        //                 $order->save();
        //                 $order->order_id = $order->id;

        //                 $payment = new Payment;
        //                 $payment->order_id = $order->id;
        //                 $payment->code_payment = $pago->id;
        //                 $payment->description = "VISITA";
        //                 $payment->state = true;
        //                 $payment->price = $request->visit_price;
        //                 $payment->save();
        //                 // $user = $request->user();
        //                 dispatch(new NotifyNewOrder($order->id,$user->email));
        //                 return response()->json([
        //                     'success' => true,
        //                     'message' => "La orden de servicio se realizó con éxito",
        //                     'order' => $order
        //                 ]);
        //             }else{
        //                 return response()->json([
        //                     'success' => false
        //                 ]);
        //             }
        //         }

        //     } catch (\Throwable $th) {
        //         Log::error($th);
        //         return response()->json([
        //             'success' => false,
        //             'message' => "La orden de servicio no se realizó"
        //         ]);
        //     }
        // }
    }

    public function save_extra_info_for_order(Request $request, $id){
        $extra_info = new ExtraInfo;
        $extra_info->order_id = $id;
        $extra_info->tipo_plaga = $request->tipo_plaga;
        $extra_info->pisos = $request->pisos;
        $extra_info->metros = $request->metros;
        $extra_info->jardin = $request->jardin;
        $extra_info->cuartos = $request->cuartos;
        $extra_info->save();

        return response()->json([
            'success' => true,
            'message' => "La orden de servicio se realizó"
        ]);
    }

    public function save_gallery(Request $request,$id){
        $image = new OrderGallery();
        $image->order_id = $id;
        $image->image = $request->image;
        $image->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function suspendQuotation($id){
        Quotation::where('id',$id)->update([
            'state' => 2
        ]);
        //pendiente notificacion a admin
    }

    public function newDate(Request $request,$id){
        $cita = new Cita;
        $cita->order_id = $id;
        $cita->service_date = $request->date;
        $cita->save();

        $order = Order::where('id',$id)->first();
        $user = User::where('id',$order->user_id)->first();
        $user->notify(new newDate($order,$user->email));
        return response()->json([
            'success' => true,
            'message' => "Cita Guardada"
        ]);
    }

    public function suspend(Request $request){
        Order::where('id',$request->order_id)->where('user_id',$request->user_id)->update([
            'price' => "CANCELLED",
            'state' => "CANCELLED"
        ]);
        $order = Order::where('id',$request->order_id)->first();
        $admin = User::where('type',"ADMINISTRATOR")->first();
        $admin->notify(new QuotationCancelled($order));
        $selected_order = DB::table('selected_orders')->where('order_id',$request->order_id)->where('state',1)->first();
        if($selected_order){
            $client = User::where('id',$order->user_id)->first();
            $client["mensajeFixerMan"] = "Tu servicio con ".$client->name." ha sido cancelado";
            $fixerman = User::where('id',$selected_order->user_id)->first();
            $fixerman->notify(new OrderCancelled($client));
            $fixerman->sendNotification($fixerman->email,'OrderCancelled',$client);
        }

    }

    public function approve(Request $request){
        $user = $request->user();
        // if($user->email == "germanruelas17@gmail.com" || $user->email == "adrimabarak@hotmail.com"){
            $tipo_de_pago = ConfigSystem::payment;
            if($tipo_de_pago["conekta"] == true){

                \Conekta\Conekta::setApiKey(ConfigSystem::conekta_key);
                Log::notice("NUEVO PAGO DE COTIZACION");
                Log::notice($request->all());
                $order = Order::where('id',$request->order_id)->first();
                $quotation = Quotation::where('id',$request->id_quotation)->first();
                $price = floatval($request->price);
                try{
                    $price = floatval($request->price);
                    if(substr($request->stripeToken,0,3) == "tok"){
                        $pago = \Conekta\Order::create(
                            [
                                "line_items" => [["name" => "PAGO POR SERVICIO","unit_price" => $price * 100,"quantity" => 1]],
                                "currency" => "MXN",
                                "customer_info" => ["name" => $user->name.' '.$user->lastName,"email" => $user->email,"phone" => $user->phone],
                                "charges" => [["payment_method" => ["type" => "card","token_id" => $request->stripeToken]]
                                ]
                            ]);
                        if($pago->payment_status == "paid"){
                            $this->guardar_pago($request->order_id,$pago->id,$price,"PAGO POR SERVICIO");
                            $this->validar_cupon($request,$order,$price);
                        }else{
                            return response()->json([
                                'success' => false
                            ]);
                        }
                    }else if(substr($request->stripeToken,0,3) == "cus"){
                        $pago = \Conekta\Order::create([
                            'currency' => 'MXN',
                            'customer_info' => ['customer_id' => $request->stripeToken,],
                            "line_items" => [["name" => "PAGO POR SERVICIO","unit_price" => $price * 100,"quantity" => 1]],
                            'charges' => [['payment_method' => ['type' => 'default']]]
                        ]);
                        if($pago->payment_status == "paid"){
                            $this->guardar_pago($request->order_id,$pago->id,$price,"PAGO POR SERVICIO");
                            $this->validar_cupon($request,$order,$price);
                        }else{
                            return response()->json([
                                'success' => false
                            ]);
                        }
                    }else if($request->stripeToken == "temp"){
                        $temp = TempPayment::where('user_id',$user->id)->where('price',$price)->first();
                        $this->guardar_pago($request->order_id,$temp->code_payment,$price,"PAGO POR SERVICIO");
                        $this->validar_cupon($request,$order,$price);
                        $temp->delete();
                    }
                    dispatch(new MailOrderAccepted($request->order_id));
                    return response()->json([
                        'success' => true,
                        'message' => "La orden de servicio se aprovó",
                        'order' => $order
                    ]);
                } catch (\Conekta\ProcessingError $error){
                    Log::error($error);
                    $name = $user->name.' '.$user->lastName;
                    dispatch(new ConektaError($name,'PAGO POR COTIZACION',$error->getMessage()));
                    return response()->json([
                        'success' => false,
                        'message' => $error->getMessage()
                    ]);
                } catch (\Conekta\ParameterValidationError $error){
                    Log::error($error);
                    $name = $user->name.' '.$user->lastName;
                    dispatch(new ConektaError($name,'PAGO POR COTIZACION',$error->getMessage()));
                    return response()->json([
                        'success' => false,
                        'message' => $error->getMessage()
                    ]);
                } catch (\Conekta\Handler $error){
                    Log::error($error);
                    $name = $user->name.' '.$user->lastName;
                    dispatch(new ConektaError($name,'PAGO POR COTIZACION',$error->getMessage()));
                    return response()->json([
                        'success' => false,
                        'message' => $error->getMessage()
                    ]);
                } catch (\Throwable $th) {
                    Log::error($th);
                    return response()->json([
                        'success' => false,
                        'message' => "Hubo un inconveniente en tu pago"
                    ]);
                }
            }else{
                Log::notice("NUEVO PAGO DE COTIZACION");
                Log::notice($request->all());
                $order = Order::where('id',$request->order_id)->first();
                $quotation = Quotation::where('id',$request->id_quotation)->first();
                $price = floatval($request->price);
                try {
                    Stripe\Stripe::setApiKey("sk_live_cgLVMsCuyCsluw3Tznx1RuPS00UJQp8Rqf");
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
                    // Log::notice($pago);
                    $payment = new Payment;
                    $payment->order_id = $request->order_id;
                    $payment->description = "PAGO POR SERVICIO";
                    $payment->code_payment = $pago->id;
                    $payment->state = true;
                    $payment->price = $price;
                    $payment->save();
                    Quotation::where('id',$request->id_quotation)->update([
                        'state' => 1
                    ]);
                } catch (\Throwable $th) {
                    Log::error($th);
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
                $check_quotations = Quotation::where('order_id',$request->order_id)->count();
                if($check_quotations == 1){
                    Order::where('id',$request->order_id)->where('user_id',$request->user_id)->update([
                        'price' => $price
                    ]);
                    if($order->visit_price == "quotation"){
                        Order::where('id',$request->order_id)->update([
                            'visit_price' => 0
                        ]);
                    }
                }

                if($order->pre_coupon != ""){
                    if($request->type_coupon == "pre_coupon"){
                        $admin_coupon = AdminCoupon::where('code',$request->coupon)->where('is_charged','N')->first();
                        if($admin_coupon){
                            AdminCoupon::where('code',$order->pre_coupon)->where('is_charged','N')->update([
                                'user_id' => $request->user_id,
                                'is_charged' => "Y",
                                'order_id' => $request->order_id
                            ]);
                        }else{
                            $new_used_coupon = Coupon::where('code',$request->coupon)->where('is_charged','N')->first();
                            if(empty($new_used_coupon)){
                                $coupon = new Coupon;
                                $coupon->code = $order->pre_coupon;
                                $coupon->user_id = $request->user_id;
                                $coupon->order_id = $request->order_id;
                                $coupon->save();
                            }else{
                                Coupon::where('code',$request->coupon)->where('is_charged',"N")->update([
                                    'is_charged' => "Y",
                                    'order_id_charged' => $request->order_id
                                ]);
                            }
                        }

                    }elseif($request->type_coupon == "Coupon"){
                        Coupon::where('code',$order->coupon)->update([
                            'is_charged' => "Y",
                            'order_id_charged' => $request->order_id
                        ]);
                    }else{
                        if($request->coupon != ""){
                            $coupon = new Coupon;
                            $coupon->code = $order->pre_coupon;
                            $coupon->user_id = $request->user_id;
                            $coupon->order_id = $request->order_id;
                            $coupon->save();
                        }
                    }
                }
                dispatch(new MailOrderAccepted($request->order_id));
                return response()->json([
                    'success' => true
                ]);
            }

        // }else{
            // try {
                // Log::notice("NUEVO PAGO DE COTIZACION");
                // Log::notice($request->all());
                // $order = Order::where('id',$request->order_id)->first();
                // $quotation = Quotation::where('id',$request->id_quotation)->first();
                // $price = floatval($request->price);
                // Log::notice($price);
                // try {
                //     Stripe\Stripe::setApiKey("sk_live_cgLVMsCuyCsluw3Tznx1RuPS00UJQp8Rqf");
                //     if(substr($request->stripeToken,0,3) == "cus"){
                //         $pago = Stripe\Charge::create ([
                //             "amount" => $price * 100,
                //             "currency" => "MXN",
                //             "customer" => $request->stripeToken,
                //             "description" => "Payment of order ".$request->order_id
                //         ]);
                //     }else{
                //         $pago = Stripe\Charge::create ([
                //             "amount" => $price * 100,
                //             "currency" => "MXN",
                //             "source" => $request->stripeToken,
                //             "description" => "Payment of order ".$request->order_id
                //         ]);
                //     }
                //     Log::notice($pago);
                //     $payment = new Payment;
                //     $payment->order_id = $request->order_id;
                //     $payment->description = "PAGO POR SERVICIO";
                //     $payment->code_payment = $pago->id;
                //     $payment->state = true;
                //     $payment->price = $price;
                //     $payment->save();
                //     Quotation::where('id',$request->id_quotation)->update([
                //         'state' => 1
                //     ]);
                // } catch (\Throwable $th) {
                //     Log::error($th);
                //     $payment = new Payment;
                //     $payment->order_id = $request->order_id;
                //     $payment->description = "PAGO POR SERVICIO";
                //     $payment->state = false;
                //     $payment->price = $price;
                //     $payment->save();
                //     return response()->json([
                //         'success' => false
                //     ]);
                // }
                // $check_quotations = Quotation::where('order_id',$request->order_id)->count();
                // if($check_quotations == 1){
                //     Order::where('id',$request->order_id)->where('user_id',$request->user_id)->update([
                //         'price' => $price
                //     ]);
                //     if($order->visit_price == "quotation"){
                //         Order::where('id',$request->order_id)->update([
                //             'visit_price' => 0
                //         ]);
                //     }
                // }

                // if($order->pre_coupon != ""){
                //     if($request->type_coupon == "pre_coupon"){
                //         $admin_coupon = AdminCoupon::where('code',$request->coupon)->where('is_charged','N')->first();
                //         if($admin_coupon){
                //             AdminCoupon::where('code',$order->pre_coupon)->where('is_charged','N')->update([
                //                 'user_id' => $request->user_id,
                //                 'is_charged' => "Y",
                //                 'order_id' => $request->order_id
                //             ]);
                //         }else{
                //             $new_used_coupon = Coupon::where('code',$request->coupon)->where('is_charged','N')->first();
                //             if(empty($new_used_coupon)){
                //                 $coupon = new Coupon;
                //                 $coupon->code = $order->pre_coupon;
                //                 $coupon->user_id = $request->user_id;
                //                 $coupon->order_id = $request->order_id;
                //                 $coupon->save();
                //             }else{
                //                 Coupon::where('code',$request->coupon)->where('is_charged',"N")->update([
                //                     'is_charged' => "Y",
                //                     'order_id_charged' => $request->order_id
                //                 ]);
                //             }
                //         }

                //     }elseif($request->type_coupon == "Coupon"){
                //         Coupon::where('code',$order->coupon)->update([
                //             'is_charged' => "Y",
                //             'order_id_charged' => $request->order_id
                //         ]);
                //     }else{
                //         if($request->coupon != ""){
                //             $coupon = new Coupon;
                //             $coupon->code = $order->pre_coupon;
                //             $coupon->user_id = $request->user_id;
                //             $coupon->order_id = $request->order_id;
                //             $coupon->save();
                //         }
                //     }
                // }
                // dispatch(new MailOrderAccepted($request->order_id));
                // return response()->json([
                //     'success' => true
                // ]);
            // } catch (\Throwable $th) {
            //     return response()->json([
            //         'success' => false
            //     ]);
            // }
        // }
    }

    public function check_active_coupon(Request $request){
        $user = $request->user();
        $check_coupon = Coupon::where('code',$user->code)->where('is_charged',"N")->first();
        return response()->json([
            "success" => true,
            "coupon" => $check_coupon
        ]);
    }

    public function coupon(Request $request){
        $user = User::where('id',$request->user_id)->first();
        $coupon = User::where('code',$request->coupon)->where('code','!=',$user->code)->first();
        $admin_coupon = AdminCoupon::where('code',$request->coupon)->where('state',1)->first();
        //Validacion si no existe ningun cupon
        if(empty($coupon) && empty($admin_coupon)){
            return response()->json([
                'success' => false,
                'message' => "Cupón no encontrado"
            ]);
        }
        //Validando cupon de admin
        $order_with_coupon = Order::where('pre_coupon',$admin_coupon->code)->where('user_id',$user->id)->get();
        if(!empty($admin_coupon) && ($order_with_coupon->isEmpty())){
            return response()->json([
                'success' => true,
                'message' => "Cupón válido",
                'discount' => $admin_coupon->discount,
                'type' => "AdminCoupon"
            ]);
        }
        //Validando cupon de usuario
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
                'message' => "Cupón válido",
                'discount' => "5",
                'type' => "Coupon"
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "No se encontraron datos"
            ]);
        }
    }

    private function guardar_orden($orden,$user_id,$image){
        $order = new Order;
        $order->user_id = $user_id;
        $order->selected_id = $orden->selected_id;
        $order->type_service = $orden->type_service;
        $order->service_date = $orden->service_date;
        $order->service_description = $orden->service_description;
        $order->service_image = $image;
        $order->address = $orden->address;
        $order->price = 'quotation';
        $order->visit_price = $orden->visit_price;
        $order->pre_coupon = $orden->coupon;
        $order->save();
        return $order;
    }
    private function guardar_pago($order_id,$code_payment,$visit_price,$descripcion){
        $payment = new Payment;
        $payment->order_id = $order_id;
        $payment->code_payment = $code_payment;
        $payment->description = $descripcion;
        $payment->state = true;
        $payment->price = $visit_price;
        $payment->save();
    }
    private function validar_cupon($request,$order,$price){
        Quotation::where('id',$request->id_quotation)->update([
            'state' => 1
        ]);

        $check_quotations = Quotation::where('order_id',$request->order_id)->count();
        if($check_quotations == 1){
            Order::where('id',$request->order_id)->where('user_id',$request->user_id)->update([
                'price' => $price
            ]);
            if($order->visit_price == "quotation"){
                Order::where('id',$request->order_id)->update([
                    'visit_price' => 0
                ]);
            }
        }

        if($order->pre_coupon != ""){
            if($request->type_coupon == "pre_coupon"){
                $admin_coupon = AdminCoupon::where('code',$request->coupon)->where('is_charged','N')->first();
                if($admin_coupon){
                    AdminCoupon::where('code',$order->pre_coupon)->where('is_charged','N')->update([
                        'user_id' => $request->user_id,
                        'is_charged' => "Y",
                        'order_id' => $request->order_id
                    ]);
                }else{
                    $new_used_coupon = Coupon::where('code',$request->coupon)->where('is_charged','N')->first();
                    if(empty($new_used_coupon)){
                        $coupon = new Coupon;
                        $coupon->code = $order->pre_coupon;
                        $coupon->user_id = $request->user_id;
                        $coupon->order_id = $request->order_id;
                        $coupon->save();
                    }else{
                        Coupon::where('code',$request->coupon)->where('is_charged',"N")->update([
                            'is_charged' => "Y",
                            'order_id_charged' => $request->order_id
                        ]);
                    }
                }

            }elseif($request->type_coupon == "Coupon"){
                Coupon::where('code',$order->coupon)->update([
                    'is_charged' => "Y",
                    'order_id_charged' => $request->order_id
                ]);
            }else{
                if($request->coupon != ""){
                    $coupon = new Coupon;
                    $coupon->code = $order->pre_coupon;
                    $coupon->user_id = $request->user_id;
                    $coupon->order_id = $request->order_id;
                    $coupon->save();
                }
            }
        }
        // dispatch(new MailOrderAccepted($request->order_id));
    }
}
