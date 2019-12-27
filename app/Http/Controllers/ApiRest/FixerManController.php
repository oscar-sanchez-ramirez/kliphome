<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use App\SelectedOrders;
use App\SelectedDelegation;
use App\SelectedCategories;
use App\User;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use App\Jobs\ApproveOrderFixerMan;
use App\Http\Controllers\ApiRest\ApiServiceController;
use App\Jobs\DisapproveOrderFixerMan;
use App\Notifications\NotifyAcceptOrder;

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
            Log::notice($order);
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

        DB::table('users')->where('id',$user_id)->update([
            $field => $value
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
            default:
                # code...
                break;
        }
    }
}
