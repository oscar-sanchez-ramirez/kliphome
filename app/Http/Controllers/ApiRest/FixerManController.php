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
        Log::info('registrando worker');
        // $this->validate($request,[
        //     'email' => 'required|email|unique:users',
        //     'name' => 'required',
        //     // 'lastName' => 'required',
        //     'password' => 'required'
        // ]);
        Log::notice($request->all());
        $user = User::create([
            'name' => $request->name,
            'lastName' => $request->last_name,
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
            $category->category_id = $request->categories[$i];
            $category->save();
        }

        return response()->json([
            'message' => "Usuario creado correctamente",
            'user' => $user
        ]);
    }
}
