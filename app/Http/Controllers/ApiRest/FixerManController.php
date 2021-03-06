<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use Stripe;
use OneSignal;
use App\Cita;
use App\User;
use App\Order;
use App\Address;
use App\Qualify;
use App\Payment;
use App\Quotation;
use Carbon\Carbon;
use App\TempPayment;
use App\FixermanStat;
use App\ConfigSystem;
use App\SelectedOrders;
use App\FixermanGallery;
use App\SelectedDelegation;
use App\SelectedCategories;
use App\Notifications\NewFixerMan;
use App\Jobs\Mail\UserConfirmation;
use App\Jobs\DisapproveOrderFixerMan;
use App\Notifications\NotifyAcceptOrder;
use App\Notifications\Database\NewQuotation;
use App\Notifications\Database\WaitQuotation;
use App\Notifications\Database\FinishedOrder;
use App\Notifications\Database\ServiceQualified;
use App\Http\Controllers\ApiRest\ApiServiceController;
use App\Notifications\Database\ApproveOrderFixerMan as DatabaseApproveOrderFixerMan;
use App\Notifications\Database\DisapproveOrderFixerMan as DatabaseDisapproveOrderFixerMan;

class FixerManController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api', ['only' => ['infoFixerman','aprobarSolicitudTecnico','updateUserField','terminarOrden','fixerManorderDetail','saveSelectedOrder','qualifyService','historyReviews','historyReviewsandOrders','filterReviews','requirequotation','paymentsFixerman']]);
    }

    public function register(Request $request){
        try {
            $this->validate($request,[
                'email' => 'required|email|unique:users',
                'name' => 'required',
                'lastName' => 'required',
                'password' => 'required'
            ]);
            $random = strtoupper(substr(md5(mt_rand()), 0, 10));
            $user = User::create([
                'name' => $request->name,
                'lastName' => $request->lastName,
                'phone' => $request->phone,
                'email' => $request->email,
                'type' => 'AppFixerMan',
                'state' => 0,
                'password' => bcrypt($request->password),
                'code' => $random,
                'email_verified_at' => Carbon::now()
            ])->toArray();

            // Address::create([
            //     'street' => $request->street,
            //     'alias' => $request->alias,
            //     'reference' => $request->reference,
            //     'postal_code' => "-",
            //     'user_id' => $user["id"],
            //     'delegation' => "-",
            //     'exterior' => $request->exterior,
            //     'interior' => $request->interior,
            //     'colonia' => "-",
            //     'municipio' => "-"
            // ]);
            //SAVE SELECTED DELEGATION
            $workAreas = explode(',',$request->workArea);
            for ($i=0; $i < count($workAreas); $i++) {
                $selected = new SelectedDelegation;
                $selected->user_id = $user["id"];
                $selected->colony = "-";
                $selected->postal_code = "-";
                $selected->municipio = $workAreas[$i];
                $selected->save();
            }
            //SAVE SELECTED CATEGORIES
            $categories = explode(',',$request->categories);
            for ($i=0; $i < count($categories); $i++) {
                $category = new SelectedCategories;
                $category->user_id = $user["id"];
                $category->category_id = $categories[$i];
                $category->save();
            }
            $general_percent = DB::table('general_stats')->where('title',"percent")->first();
            $stat = new FixermanStat;
            $stat->user_id = $user["id"];
            $stat->percent = $general_percent->value;
            $stat->save();

            $client = User::where('type',"ADMINISTRATOR")->first();
            $client->notify(new NewFixerMan($user));
            dispatch(new UserConfirmation($user));
            return response()->json([
                'status' => true,
                'message' => "Tu cuenta se cre?? exitosamente, evaluaremos tu perfil.",
                'user' => $user
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => false,
                'fail' => "No se pudo registrar al trabajador, porfavor verifique sus datos"
            ]);
        }

    }

    public function saveSelectedOrder(Request $request){
        $check = SelectedOrders::where('order_id',$request->order_id)->first();
        if(!$check || $check->state == 0){
            try {
                $order = Order::where('id',$request->order_id)->first();
                $new_selected_order = new SelectedOrders;
                $new_selected_order->user_id = $request->user_id;
                $new_selected_order->order_id = $request->order_id;
                $new_selected_order->state = $request->state;
                $new_selected_order->save();
                if($request->state == 1){
                    Order::where('id',$request->order_id)->update([
                        'state' => 'FIXERMAN_APPROVED'
                    ]);

                    $user = User::where('id',$order->user_id)->first();
                    $fixerman = $request->user();
                    $new_selected_order["mensajeClient"] = $fixerman->name." acept?? tu trabajo. ??chale un vistazo";
                    $new_selected_order["type"] = 'App\Notifications\NotifyAcceptOrder';
                    $user->notify(new NotifyAcceptOrder($new_selected_order,$user->email));
                    return Response(json_encode(array('success' => 1,'message'=>'Fuiste asignado correctamente')));
                }else{
                    DB::table('fixerman_stats')->where('user_id',$request->user_id)->increment('rejected');
                    return Response(json_encode(array('success' => 0,'message'=>'Eliminado correctamente')));
                }

            } catch (\Throwable $th) {
                Log::error($th);
                return Response(json_encode(array('success' => 0,'message'=>'Error al guardar, intente de nuevo mas tarde')));
            }
        }else{
            return Response(json_encode(array('success' => 0,'message'=>'Ya se asigno un t??cnico para esta orden')));
        }
    }

    public function aprobarSolicitudTecnico(Request $request){
        $order = Order::where('id',$request->order_id)->first();
        $fixerman = User::where('id',$request->fixerman_id)->first();
        $date = Carbon::createFromFormat('Y/m/d H:i', $order->service_date);
        $user_order = User::where('id',$order->user_id)->first();

        $otherRequest = DB::table('selected_orders')->where('user_id','!=',$fixerman->id)->where('order_id',$order->id)->get();
        if(!empty($otherRequest)){
            $order["mensajeClient"] = ucwords(strtolower($user_order->name))." ya asign?? su trabajo con otro t??cnico";
            $order["mensajeFixerMan"] = ucwords(strtolower($user_order->name))." ya asign?? su trabajo con otro t??cnico";
            foreach ($otherRequest as $key) {
                $notFixerman = User::where('id',$key->user_id)->first();
                $notFixerman->notify(new DatabaseDisapproveOrderFixerMan($notFixerman));
                $notification = $notFixerman->notifications()->first();
                $notFixerman->notification_id = $notification->id;
                $notFixerman->sendNotification($notFixerman->email,'DisapproveOrderFixerMan',$notFixerman);
            }
            DB::table('selected_orders')->where('user_id','!=',$fixerman->id)->where('order_id',$order->id)->update([
                'state' => 0
            ]);
        }

        //Notification for Fixerman
        $order["mensajeClient"] = "??Listo! Se ha Confirmado tu trabajo con ".$fixerman->name." para el d??a ".Carbon::parse($date)->format('d,M H:i');
        $order["mensajeFixerMan"] = "??Listo! Se ha Confirmado tu trabajo con ".$user_order->name." para el d??a ".Carbon::parse($date)->format('d,M H:i');
        $order["type"] = "App\Notifications\Database\ApproveOrderFixerMan";
        $fixerman->notify(new DatabaseApproveOrderFixerMan($order,$fixerman->email));
        $notification = $fixerman->notifications()->first();
        $order->notification_id = $notification->id;
        $fixerman->sendNotification($fixerman->email,'ApproveOrderFixerMan',$order);
        Order::where('id',$request->order_id)->update([
            'state' => 'FIXERMAN_APPROVED'
        ]);
    }

    public function eliminarSolicitudTecnico(Request $request){
        $fixerman = User::where('id',$request->fixerman_id)->first();

        $fixerman->notify(new DatabaseDisapproveOrderFixerMan($fixerman));
        $notification = $fixerman->notifications()->first();
        $fixerman->notification_id = $notification->id;
        $fixerman->sendNotification($fixerman->email,'DisapproveOrderFixerMan',$fixerman);

        Order::where('id',$request->order_id)->update([
            'state' => 'FIXERMAN_NOTIFIED'
        ]);
        SelectedOrders::where('user_id',$request->fixerman_id)->where('order_id',$request->order_id)->update([
            'state' => 0
        ]);
    }

    public function terminarOrden(Request $request){
        $order_id = $request->order_id;
        $fixerman_id = $request->fixerman_id;
        //Get User and Order
        $order = Order::where('id',$order_id)->first();
        $fixerman = User::where('id',$fixerman_id)->first();
        //Notify
        $order["mensajeClient"] = "??Gracias por usar KlipHome! Tu servicio con ".ucfirst(strtolower($fixerman->name))." ha terminado, ??Calif??calo ahora! ";
        $order["type"] = 'App\Notifications\Database\FinishedOrder';
        $client = User::where('id',$order->user_id)->first();
        $client->notify(new FinishedOrder($order));
        //Onesignal Notification
        $content = $order;
        OneSignal::sendNotificationUsingTags(
            ucfirst(strtolower($fixerman->name))." ha marcardo el servicio como terminado. ??Val??ralo ahora!",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $client->email],
            ),
            $url = null,
            $content,
            $data = null,
            $buttons = null,
            $schedule = null
        );

        //Update order
        Order::where('id',$order_id)->update([
            'finished_at' => Carbon::now(),
            'state' => 'FIXERMAN_DONE'
        ]);
        FixermanStat::where('user_id',$fixerman_id)->increment('completed');
    }

    public function qualifyService(Request $request){
        $user = $request->user();

        // if($user->email == "germanruelas17@gmail.com" || $user->email == "adrimabarak@hotmail.com"){
            $tipo_de_pago = ConfigSystem::payment;
            if($tipo_de_pago["conekta"] == true){
                // Log::notice($request->all());
                try {
                    $price = floatval($request->price);
                    if($price != 0){
                        try {

                            \Conekta\Conekta::setApiKey(ConfigSystem::conekta_key);
                            if(substr($request->stripeToken,0,3) == "tok"){
                                $pago = \Conekta\Order::create(
                                    [
                                      "line_items" => [["name" => "PROPINA POR SERVICIO","unit_price" => $price * 100,"quantity" => 1]],
                                      "currency" => "MXN",
                                      "customer_info" => ["name" => $user->name.' '.$user->lastName,"email" => $user->email,"phone" => $user->phone],
                                      "charges" => [["payment_method" => ["type" => "card","token_id" => $request->stripeToken]]
                                      ]
                                    ]
                                  );
                                  $this->guardar_pago($request->order_id,$pago->id,$price,"PROPINA POR SERVICIO");
                            }else if(substr($request->stripeToken,0,3) == "cus"){
                                $pago = \Conekta\Order::create([
                                    'currency' => 'MXN',
                                    'customer_info' => ['customer_id' => $request->stripeToken,],
                                    "line_items" => [["name" => "PROPINA POR SERVICIO","unit_price" => $price * 100,"quantity" => 1]],
                                    'charges' => [['payment_method' => ['type' => 'default']]]
                                  ]);
                                  $this->guardar_pago($request->order_id,$pago->id,$price,"PROPINA POR SERVICIO");
                            }else if($request->stripeToken == "temp"){
                                $temp = TempPayment::where('user_id',$user->id)->where('price',$price)->first();
                                $this->guardar_pago($request->order_id,$temp->code_payment,$price,"PROPINA POR SERVICIO");
                                $temp->delete();
                            }
                        } catch (\Throwable $th) {
                            Log::error($th);
                            return response()->json([
                                'success' => false
                            ]);
                        }
                    }
                    $qualify = new Qualify;
                    $qualify->user_id = $request->fixerman_id;
                    $qualify->selected_order_id = $request->idOrderAccepted;
                    $qualify->presentation = $request->presentation;
                    $qualify->puntuality = $request->puntuality;
                    $qualify->problemSolve = $request->problemSolve;
                    $qualify->comment = $request->comment;
                    $qualify->tip = $request->price;
                    $qualify->save();
                    //Update order
                    DB::table('selected_orders as so')
                    ->join('orders as o', 'so.order_id','o.id')
                    ->where('so.id',$request->idOrderAccepted)
                    ->update([ 'o.state' => "QUALIFIED" ]);
                    //Database notification
                    $qualify["mensajeFixerMan"] = "??Gracias por usar KlipHome! Tu servicio fue calificado, ????chale un vistazo! ";
                    $user = User::where('id',$request->fixerman_id)->first();
                    $user->notify(new ServiceQualified($qualify));
                    //OneSignal notification
                    $notification = $user->notifications()->first();
                    $user->notification_id = $notification->id;
                    $user->sendNotification($user->email,'ServiceQualified',$qualify);

                    return response()->json([
                        'success' => true
                    ]);
                } catch (\Throwable $th) {
                    Log::error($th);
                    return response()->json([
                        'success' => false
                    ]);
                }
            }else{
                // Log::notice($request->all());
                try {
                    $user = User::where('id',$request->fixerman_id)->first();
                    $price = floatval($request->price);
                    if($price != 0){
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

                            $payment = new Payment;
                            $payment->order_id = $request->order_id;
                            $payment->description = "PROPINA POR SERVICIO";
                            $payment->state = true;
                            $payment->code_payment = $pago->id;
                            $payment->price = $price;
                            $payment->save();
                        } catch (\Throwable $th) {
                            Log::error($th);
                            return response()->json([
                                'success' => false,
                                'message' => "No se pudo realizar la calificaci??n por un error en tu tarjeta"
                            ]);
                        }
                    }
                    $qualify = new Qualify;
                    $qualify->user_id = $request->fixerman_id;
                    $qualify->selected_order_id = $request->idOrderAccepted;
                    $qualify->presentation = $request->presentation;
                    $qualify->puntuality = $request->puntuality;
                    $qualify->problemSolve = $request->problemSolve;
                    $qualify->comment = $request->comment;
                    $qualify->tip = $request->price;
                    $qualify->save();
                    //Update order
                    DB::table('selected_orders as so')
                    ->join('orders as o', 'so.order_id','o.id')
                    ->where('so.id',$request->idOrderAccepted)
                    ->update([ 'o.state' => "QUALIFIED" ]);
                    //Database notification
                    $qualify["mensajeFixerMan"] = "??Gracias por usar KlipHome! Tu servicio fue calificado, ????chale un vistazo! ";
                    $user->notify(new ServiceQualified($qualify));
                    //OneSignal notification
                    $notification = $user->notifications()->first();
                    $user->notification_id = $notification->id;
                    $user->sendNotification($user->email,'ServiceQualified',$qualify);

                    return response()->json([
                        'success' => true
                    ]);
                } catch (\Throwable $th) {
                    Log::error($th);
                    return response()->json([
                        'success' => false
                    ]);
                }
            }
        // }else{
        //     Log::notice($request->all());
        //     try {
        //         $user = User::where('id',$request->fixerman_id)->first();
        //         $price = floatval($request->price);
        //         if($price != 0){
        //             try {
        //                 Stripe\Stripe::setApiKey("sk_live_cgLVMsCuyCsluw3Tznx1RuPS00UJQp8Rqf");
        //                 if(substr($request->stripeToken,0,3) == "cus"){
        //                     $pago = Stripe\Charge::create ([
        //                         "amount" => $price * 100,
        //                         "currency" => "MXN",
        //                         "customer" => $request->stripeToken,
        //                         "description" => "Payment of order ".$request->order_id
        //                     ]);
        //                 }else{
        //                     $pago = Stripe\Charge::create ([
        //                         "amount" => $price * 100,
        //                         "currency" => "MXN",
        //                         "source" => $request->stripeToken,
        //                         "description" => "Payment of order ".$request->order_id
        //                     ]);
        //                 }

        //                 $payment = new Payment;
        //                 $payment->order_id = $request->order_id;
        //                 $payment->description = "PROPINA POR SERVICIO";
        //                 $payment->state = true;
        //                 $payment->code_payment = $pago->id;
        //                 $payment->price = $price;
        //                 $payment->save();
        //             } catch (\Throwable $th) {
        //                 Log::error($th);
        //                 return response()->json([
        //                     'success' => false,
        //                     'message' => "No se pudo realizar la calificaci??n por un error en tu tarjeta"
        //                 ]);
        //                 // $payment = new Payment;
        //                 // $payment->order_id = $request->order_id;
        //                 // $payment->description = "PROPINA POR SERVICIO";
        //                 // $payment->state = false;
        //                 // $payment->price = $price;
        //                 // $payment->save();

        //             }
        //         }
        //         $qualify = new Qualify;
        //         $qualify->user_id = $request->fixerman_id;
        //         $qualify->selected_order_id = $request->idOrderAccepted;
        //         $qualify->presentation = $request->presentation;
        //         $qualify->puntuality = $request->puntuality;
        //         $qualify->problemSolve = $request->problemSolve;
        //         $qualify->comment = $request->comment;
        //         $qualify->tip = $request->price;
        //         $qualify->save();
        //         //Update order
        //         DB::table('selected_orders as so')
        //         ->join('orders as o', 'so.order_id','o.id')
        //         ->where('so.id',$request->idOrderAccepted)
        //         ->update([ 'o.state' => "QUALIFIED" ]);
        //         //Database notification
        //         $qualify["mensajeFixerMan"] = "??Gracias por usar KlipHome! Tu servicio fue calificado, ????chale un vistazo! ";
        //         $user->notify(new ServiceQualified($qualify));
        //         //OneSignal notification
        //         $notification = $user->notifications()->first();
        //         $user->notification_id = $notification->id;
        //         $user->sendNotification($user->email,'ServiceQualified',$qualify);

        //         return response()->json([
        //             'success' => true
        //         ]);
        //     } catch (\Throwable $th) {
        //         Log::error($th);
        //         return response()->json([
        //             'success' => false
        //         ]);
        //     }
        // }
    }

    public function requirequotation(Request $request){
        $order = Order::where('id',$request->order_id)->first();
        $order["mensajeClient"] = "Estamos realizando tu cotizaci??n, en breve la recibir??s ";
        $order["type"] = "App\Notifications\Database\WaitQuotation";
        $client = User::where('id',$order->user_id)->first();
        $client->notify(new WaitQuotation($order));
        $content = $order;
        OneSignal::sendNotificationUsingTags(
            "Estamos realizando tu cotizaci??n, en breve la recibir??s",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $client->email],
            ),
            $url = null,
            $content,
            $data = null,
            $buttons = null,
            $schedule = null
        );
        $admin = User::where('type',"ADMINISTRATOR")->first();
        $admin->notify(new NewQuotation($order));

        Order::where('id',$request->order_id)->update([
            'price' => "waitquotation"
        ]);
        return response()->json([
            'success' => true
        ]);
    }

    public function paymentsFixerman(Request $request){
        $user = $request->user();
        // Log::notice($user);
        // $user = User::where('id',$user->id)->first();
        $payments = DB::table('selected_orders as so')->join('orders as o','o.id','so.order_id')->leftJoin('quotations as q','o.id','q.order_id')->join('payments as p','p.order_id','o.id')
        ->select('p.*','q.workforce')->where('so.user_id',$user->id)->where('so.state',1)->where('p.state',1)->get();

        return response()->json([
            'payments' => $payments
        ]);
    }

    public function infoFixerman($id,$order_id){
        $order = Order::where('id',$order_id)->first();
        $order_category = new ApiServiceController();
        $user = User::where('id',$id)->where('type','AppFixerMan')->first();
        $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$user->id)->get();
        // $qualifies = DB::table('qualifies as q')
        // ->join('selected_orders as so','q.selected_order_id','so.id')
        // ->join('orders as o','so.order_id','o.id')
        // ->join('users as u','o.user_id','u.id')
        // ->select('q.presentation','q.puntuality','q.problemSolve','q.comment','q.created_at','u.name','u.lastName','u.avatar')
        // ->where('q.id',$id)
        // ->take(5)->get();
        $qualifies = DB::table('qualifies as q')->join('selected_orders as so','so.id','q.selected_order_id')->join('orders as o','o.id','so.order_id')->join('users as u','u.id','o.user_id')
        ->select('q.*','u.avatar','u.name','u.lastName')->where('q.user_id',$id)->orderBy('q.created_at','DESC')->take(5)->get();
        if($order->state == "FIXERMAN_APPROVED" || $order->state == "QUALIFIED"){
            return response()->json([
                'message' => "Este trabajo ya ha sido asignado",
                'order' => $order,
                'user' => $user,
                'categories' => $categories,
                'qualifies' => $qualifies,
                'order_category' => $order_category->table($order->type_service,$order->selected_id)
            ]);
        }
        return response()->json([
            'user' => $user,
            'order' => $order,
            'categories' => $categories,
            'qualifies' => $qualifies,
            'order_category' => $order_category->table($order->type_service,$order->selected_id)
        ]);
    }

    public function updateUserField(Request $request){

        $field = $this->fields($request->field);
        $value = $request->value;
        $user_id = $request->user_id;
        if($field == 'password'){
            DB::table('users')->where('id',$user_id)->update([
                $field => bcrypt($value)
            ]);
        }else if($field == "Servicios"){
            DB::table('selected_categories')->where('user_id',$user_id)->delete();
            $new_value = explode(',', $value);
            for ($i=0; $i < count($new_value); $i++) {
                $sel = new SelectedCategories;
                $sel->user_id = $user_id;
                $sel->category_id = $new_value[$i];
                $sel->save();
            }
        }else if($field == "Colonias"){
            SelectedDelegation::where('user_id',$user_id)->delete();

            $workAreas = explode(',',$value);
            for ($i=0; $i < count($workAreas); $i++) {
                $selected = new SelectedDelegation;
                $selected->user_id =  $user_id;
                $selected->colony = "-";
                $selected->postal_code = "-";
                $selected->municipio = $workAreas[$i];
                $selected->save();
            }
        }
        else{
            DB::table('users')->where('id',$user_id)->update([
                $field => $value
            ]);
        }
    }

    public function historyReviews($id){
        $reviews = DB::table('qualifies as q')->join('selected_orders as so','so.id','q.selected_order_id')->join('orders as o','o.id','so.order_id')->join('users as u','u.id','o.user_id')
        ->select('q.*','u.avatar','u.name','u.lastName')->where('q.user_id',$id)->orderBy('q.created_at','DESC')->get();

        return response()->json([
            'reviews' => $reviews
        ]);
    }

    public function historyReviewsandOrders($id){
        $reviews = DB::table('qualifies as q')
        ->join('selected_orders as so','so.id','q.selected_order_id')
        ->join('orders as o','o.id','so.order_id')->join('users as u','u.id','o.user_id')
        ->select('q.*','u.avatar','u.name','u.lastName')->where('q.user_id',$id)->orderBy('q.created_at','DESC')->get();
        $selected_orders = DB::table('selected_orders')->where('user_id',$id)->get();
        $completed = DB::table('selected_orders as so')->join('orders as o','o.id','so.order_id')->where('so.user_id',$id)->where('o.state',"FIXERMAN_DONE")->count();
        return response()->json([
            'reviews' => $reviews,
            'selected_orders' => $selected_orders,
            'completed' => $completed
        ]);
    }

    public function filterReviews($user_id,$filter){
        switch ($filter) {
            case 'all':
                $reviews = DB::table('qualifies as q')->join('orders as o','o.id','q.selected_order_id')->join('users as u','u.id','o.user_id')
                ->select('q.*','u.avatar','u.name','u.lastName')->where('q.user_id',$user_id)->orderBy('q.created_at','DESC')->get();
                break;
            default:
                $hoy = Carbon::now()->format('Y-m-d H:i:i');
                $sub = Carbon::createFromFormat('Y-m-d H:i:i', $hoy);
                $days = $sub->subDays($filter)->format('Y-m-d H:i:i');
                $reviews = DB::table('qualifies as q')->join('orders as o','o.id','q.selected_order_id')->join('users as u','u.id','o.user_id')
                ->select('q.*','u.avatar','u.name','u.lastName')->whereDate('q.created_at','>=',$days)->whereDate('q.created_at','<=',$hoy)->where('q.user_id',$user_id)->orderBy('q.created_at','DESC')->get();
                break;
        }
        return response()->json([
            'reviews' => $reviews
        ]);
    }

    public function fixerManorderDetail($order_id){
        $orders = DB::table('orders as o')
        ->join('addresses as a','o.address','a.id')
        ->leftJoin('selected_orders as so','o.id','so.order_id')
        ->leftJoin('users as u','u.id','o.user_id')
        ->leftJoin('quotations as q','q.order_id','o.id')
        ->select('o.*','a.alias','a.street as address','a.reference','a.exterior','a.interior','a.municipio','u.name','u.lastName','u.id as fixerman_id','u.avatar','so.created_at as orderAcepted','so.id as idOrderAccepted','q.workforce')
        ->where('o.id',$order_id)->where('so.state',1)->get();
        $fetch_categories = new ApiServiceController();
        foreach ($orders as $key) {
            $category = $fetch_categories->table($key->type_service, $key->selected_id);
            $key->category = $category[0]->category;
            if ($key->type_service == "Category") {
                $key->sub_category = "-";
            }else{
                $key->sub_category = $category[0]->sub_category;
            }
            $key->serviceTrait = $category[0]->service;
            $key->visit_price = $category[0]->visit_price;
        }
        return Response(json_encode(array('orders' => $orders)));
    }

    public function clientorderDetail($order_id){
        $orders = DB::table('orders as o')
        ->join('addresses as a','o.address','a.id')
        ->leftJoin('selected_orders as so','o.id','so.order_id')
        ->leftJoin('users as u','u.id','so.user_id')
        ->select('o.*','a.alias','a.street as address','a.reference','a.exterior','a.interior','a.municipio','u.name','u.lastName','u.id as fixerman_id','u.avatar','so.created_at as orderAcepted','so.id as idOrderAccepted')
        ->where('o.id',$order_id)->where('so.state',1)->get();
        if(count($orders) == 0){
            $orders = DB::table('orders as o')
            ->join('addresses as a','o.address','a.id')
            ->leftJoin('selected_orders as so','o.id','so.order_id')
            ->leftJoin('users as u','u.id','so.user_id')
            ->select('o.*','a.alias','a.street as address','a.reference','a.exterior','a.interior','a.municipio','u.name','u.lastName','u.id as fixerman_id','u.avatar','so.created_at as orderAcepted','so.id as idOrderAccepted')
            ->where('o.id',$order_id)->get();
        }
        $fetch_categories = new ApiServiceController();
        foreach ($orders as $key) {
            $category = $fetch_categories->table($key->type_service, $key->selected_id);
            $key->category = $category[0]->category;
            if ($key->type_service == "Category") {
                $key->sub_category = "-";
            }else{
                $key->sub_category = $category[0]->sub_category;
            }
            $key->serviceTrait = $category[0]->service;
            $key->visit_price = $category[0]->visit_price;
        }
        return Response(json_encode(array('orders' => $orders)));
    }

    public function extra_info_for_order_detail($order_id){
        $dates = Cita::where('order_id',$order_id)->get();
        $quotations = Quotation::where('order_id',$order_id)->where(function ($query){
            $query->where('state',0)->orWhere('state',1);
        })->get();
        return response()->json([
            'dates' => $dates,
            'quotations' => $quotations
        ]);
    }

    private function fields($field){
        switch ($field) {
            case 'Nombre':
                return "name";
                break;
            case 'Apellido':
                return "lastName";
                break;
            case 'Telefono':
                return "phone";
                break;
            case 'email':
                return "email";
                break;
            case 'Contrase??a':
                return "password";
                break;
            case 'avatar':
                return "avatar";
                break;
            case 'Servicios':
                return "Servicios";
                break;
            case 'Colonias':
                return "Colonias";
                break;
            default:
                # code...
                break;
        }
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

    public function gallery(Request $request){
        $gallery = new FixermanGallery;
        $gallery->user_id = $request->user_id;
        $gallery->path = $request->path;
        $gallery->type = $request->type;
        $gallery->save();
        return response()->json([
            'success' => true
          ]);
    }

}
