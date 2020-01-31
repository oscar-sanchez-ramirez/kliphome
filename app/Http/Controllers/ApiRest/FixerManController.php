<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use Stripe;
use OneSignal;
use App\User;
use App\Order;
use App\Qualify;
use App\FixermanStat;
use App\SelectedOrders;
use App\SelectedDelegation;
use App\SelectedCategories;
use Carbon\Carbon;
use App\Http\Controllers\ApiRest\ApiServiceController;
use App\Jobs\ApproveOrderFixerMan;
use App\Jobs\DisapproveOrderFixerMan;
use App\Notifications\NotifyAcceptOrder;
use App\Notifications\Database\FinishedOrder;
use App\Notifications\Database\ServiceQualified;

class FixerManController extends ApiController
{
    public function register(Request $request){
        try {
            $this->validate($request,[
                'email' => 'required|email|unique:users',
                'name' => 'required',
                'lastName' => 'required',
                'password' => 'required'
            ]);
            $user = User::create([
                'name' => $request->name,
                'lastName' => $request->lastName,
                'phone' => $request->phone,
                'email' => $request->email,
                'type' => 'AppFixerMan',
                'state' => 0,
                'password' => bcrypt($request->password),
                'code' => str_random(10)
            ])->toArray();

            //SAVE SELECTED DELEGATION
            $selected = new SelectedDelegation;
            $selected->user_id = $user["id"];
            $selected->delegation_id = $request->workArea;
            $selected->save();
            //SAVE SELECTED CATEGORIES
            $categories = explode(',',$request->categories);
            for ($i=0; $i < count($categories); $i++) {
                $category = new SelectedCategories;
                $category->user_id = $user["id"];
                $category->category_id = $categories[$i];
                $category->save();
            }
            FixermanStat::create([
                'user_id' => $user["id"]
            ]);

            return response()->json([
                'message' => "Tu cuenta se creó exitosamente, evaluaremos tu perfil.",
                'user' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'fail' => "No se pudo registrar al trabajador, porfavor verifique sus datos"
            ]);
        }

    }

    public function saveSelectedOrder(Request $request){
        try {
            $order = Order::where('id',$request->order_id)->first();
            // if($order->state)
            $new_selected_order = new SelectedOrders;
            $new_selected_order->user_id = $request->user_id;
            $new_selected_order->order_id = $request->order_id;
            $new_selected_order->state = $request->state;
            $new_selected_order->save();
            if($request->state == 1){
                $user = User::where('id',$order->user_id)->first();
                $fixerman = User::where('id',$request->user_id)->first();
                $new_selected_order["mensajeClient"] = $fixerman->name." aceptó tu trabajo. Échale un vistazo";
                $user->notify(new NotifyAcceptOrder($new_selected_order,$user->email));
            }
            return Response(json_encode(array('success' => "Se mandó solicitud de servicio")));
        } catch (\Throwable $th) {
            return Response(json_encode(array('failed' => "Error al guardar")));
        }
    }

    public function aprobarSolicitudTecnico(Request $request){
        dispatch(new ApproveOrderFixerMan($request->fixerman_id,$request->order_id));
        return back();
    }

    public function eliminarSolicitudTecnico(Request $request){
        dispatch(new DisapproveOrderFixerMan($request->fixerman_id,$request->order_id));
        return back();
    }

    public function terminarOrden(Request $request){
        $order_id = $request->order_id;
        $fixerman_id = $request->fixerman_id;
        //Get User and Order
        $order = Order::where('id',$order_id)->first();
        $fixerman = User::where('id',$fixerman_id)->first();
        //Notify
        $order["mensajeClient"] = "¡Gracias por usar KlipHome! Tu servicio con ".ucfirst(strtolower($fixerman->name))." ha terminado, ¡Califícalo ahora! ";
        $client = User::where('id',$order->user_id)->first();
        $client->notify(new FinishedOrder($order));
        //Onesignal Notification
        OneSignal::sendNotificationUsingTags(
            ucfirst(strtolower($fixerman->name))." ha marcardo el servicio como terminado. ¡Valóralo ahora!",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $client->email],
            ),
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
        FixermanStat::where('user_id',$fixerman_id)->increment('completed');
    }

    public function qualifyService(Request $request){
        try {
            $user = User::where('id',$request->fixerman_id)->first();
            $price = floatval($request->price);
            if($price != 0){
                Stripe\Stripe::setApiKey("sk_test_brFGYtiWSjTpj5z7y3B8lwsP");
                Stripe\Charge::create ([
                    "amount" => $price * 100,
                    "currency" => "MXN",
                    "source" => $request->stripeToken,
                    "description" => "Payment a ".$user->name." ".$user->lastName
                ]);
            }
            $qualify = new Qualify;
            $qualify->user_id = $request->fixerman_id;
            $qualify->selected_order_id = $request->idOrderAccepted;
            $qualify->presentation = $request->presentation;
            $qualify->puntuality = $request->puntuality;
            $qualify->problemSolve = $request->problemSolve;
            $qualify->comment = $request->comment;
            $qualify->tip = $request->tip;
            $qualify->save();

            //Database notification
            $qualify["mensajeFixerMan"] = "¡Gracias por usar KlipHome! Tu servicio fue calificado, ¡Échale un vistazo! ";
            $user->notify(new ServiceQualified($qualify));
            //OneSignal notification
            $user->sendNotification($user->email,'ServiceQualified');
            //Update order
            DB::table('selected_orders as so')
            ->join('orders as o', 'so.order_id','o.id')
            ->where('so.id',$request->idOrderAccepted)
            ->update([ 'o.state' => "QUALIFIED" ]);
            return response()->json([
                'success' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function infoFixerman($id,$order_id){
        $order = Order::where('id',$order_id)->first();

        $order_category = new ApiServiceController();
        $user = User::where('id',$id)->where('type','AppFixerMan')->first();
        $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$user->id)->get();
        $qualifies = DB::table('qualifies as q')
        ->join('selected_orders as so','q.selected_order_id','so.id')
        ->join('orders as o','so.order_id','o.id')
        ->join('users as u','o.user_id','u.id')
        // ->where('q.user_id',$id)
        ->select('q.presentation','q.puntuality','q.problemSolve','q.comment','q.created_at','u.name','u.lastName','u.avatar')
        ->take(5)->get();
        if($order->state == "FIXERMAN_APPROVED" || $order->state == "QUALIFIED"){
            return response()->json([
                'message' => "Este trabajo ya ha sido asignado",
                'user' => $user,
                'categories' => $categories,
                'qualifies' => $qualifies,
                'order_category' => $order_category->table($order->type_service,$order->selected_id)
            ]);
        }
        return response()->json([
            'user' => $user,
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
            Log::notice($new_value);
            for ($i=0; $i < count($new_value); $i++) {
                $sel = new SelectedCategories;
                $sel->user_id = $user_id;
                $sel->category_id = $new_value[$i];
                $sel->save();
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
        $reviews = DB::table('qualifies as q')->join('orders as o','o.id','q.selected_order_id')->join('users as u','u.id','o.user_id')
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
            case 'Contraseña':
                return "password";
                break;
            case 'avatar':
                return "avatar";
                break;
            case 'Servicios':
                return "Servicios";
                break;
            default:
                # code...
                break;
        }
    }

}
