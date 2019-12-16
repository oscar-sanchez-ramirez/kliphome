<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use App\SelectedOrders;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use App\Notifications\NotifyAcceptOrder;

class FixerManController extends ApiController
{
    public function register(Request $request){
        try {
            Log::notice($request->all());
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
            Log::notice($user);

            //SAVE SELECTED DELEGATION
            $selected = new SelectedDelegation;
            $selected->user_id = $user["id"];
            $selected->delegation_id = $request->workArea;
            $selected->save();
            Log::notice($request->workArea);
            //SAVE SELECTED CATEGORIES
            $categories = explode(',',$request->categories);
            Log::notice($categories);
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
            $new_selected_order = new SelectedOrders;
            $new_selected_order->user_id = $request->user_id;
            $new_selected_order->order_id = $request->order_id;
            $new_selected_order->state = $request->state;
            $new_selected_order->save();
            if($request->state == 1){
                $user = User::where('email',"admin@kliphome.com")->first();
                $user->notify(new NotifyAcceptOrder($new_selected_order));
            }
            return Response(json_encode(array('success' => "Se mandó solicitud de servicio")));
        } catch (\Throwable $th) {
            return Response(json_encode(array('failed' => "Error al guardar")));
        }

    }
}
