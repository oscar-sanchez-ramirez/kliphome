<?php

namespace App\Http\Controllers\Admin\Libs;
// use App\User;
use DB;


/**
 *
 */
trait UserLib
{
    protected function userInfo($id){
        // return $request->all();
        $user = User::where('id',$id)->first();
        if($user->type == "AppFixerMan"){
            // $delegation = DB::table('selected_delegations as s')->join('delegations as d','s.delegation_id','d.id')->select('s.id','d.id as delegation_id','d.title')->where('s.user_id',$user->id)->get();
            // $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$user->id)->get();
            return response()->json([
                'user' => $user,
                'delegations' => $delegation,
                'categories' => $categories
            ]);
        }elseif($user->type == "AppUser"){
            $address = Address::where('user_id',$user->id)->get();
            return array($user,$address);
        }

    }

}
