<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use OneSignal;
use App\User;
use App\Order;
use App\Qualify;
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
        Log::notice($request->all());
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
    }

    public function qualifyService(Request $request){
        $qualify = new Qualify;
        $qualify->user_id = $request->fixerman_id;
        $qualify->selected_order_id = $request->idOrderAccepted;
        $qualify->presentation = $request->presentation;
        $qualify->puntuality = $request->puntuality;
        $qualify->problemSolve = $request->problemSolve;
        $qualify->comment = $request->comment;
        $qualify->tip = $request->tip;
        $qualify->save();
        $user = User::where('id',$request->fixerman_id)->first();
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

    }

    public function infoFixerman($id,$order_id){
        $order = Order::where('id',$order_id)->first();

        $order_category = new ApiServiceController();
        $user = User::where('id',$id)->where('type','AppFixerMan')->first();
        $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$user->id)->get();
        if($order->state == "FIXERMAN_APPROVED"){
            return response()->json([
                'message' => "Este trabajo ya ha sido asignado",
                'user' => $user,
                'categories' => $categories,
                'order_category' => $order_category->table($order->type_service,$order->selected_id)
            ]);
        }
        return response()->json([
            'user' => $user,
            'categories' => $categories,
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
        }else{
            DB::table('users')->where('id',$user_id)->update([
                $field => $value
            ]);
        }
    }

    public function historyReviews($id){
        $reviews = DB::table('qualifies as q')->join('orders as o','o.id','q.selected_order_id')->join('users as u','u.id','o.user_id')
        ->select('q.*','u.avatar','u.name','u.lastName')->where('q.user_id',$id)->get();

        return response()->json([
            'reviews' => $reviews
        ]);
    }

    public function filterReviews($user_id,$filter){
        switch ($filter) {
            case 'all':
                $reviews = DB::table('qualifies as q')->join('orders as o','o.id','q.selected_order_id')->join('users as u','u.id','o.user_id')
                ->select('q.*','u.avatar','u.name','u.lastName')->where('q.user_id',$user_id)->get();
                break;
            default:
                $hoy = Carbon::now()->format('Y-m-d H:i:i');
                return $days = $hoy->subDays($filter)->format('Y-m-d H:i:i');
                return $reviews = DB::table('qualifies as q')->join('orders as o','o.id','q.selected_order_id')->join('users as u','u.id','o.user_id')
                ->select('q.*','u.avatar','u.name','u.lastName')->whereDate('q.created_at','>=',$days)->whereDate('q.created_at','<=',$hoy)->where('q.user_id',$user_id)->get();
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
            default:
                # code...
                break;
        }
    }

}
