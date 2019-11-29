<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use App\SelectedCategories;
use App\SelectedDelegation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}
