<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\AproveFixerMan;
use App\User;
use DB;


class FixerManController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $users = User::where('type','AppFixerMan')->get();
        return view('admin.fixerman.index')->with('users',$users);
    }
    public function detail($id){
        $delegation = DB::table('selected_delegations as s')->join('delegations as d','s.delegation_id','d.id')->select('s.id','d.id as delegation_id','d.title')->where('s.user_id',$id)->get();
        $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$id)->get();
        return response()->json([
            'delegations' => $delegation,
            'categories' => $categories
        ]);
    }
    public function aprove(Request $request){
        User::where('id',$request->fixerman_id)->update([
            'state' => true
        ]);
        // dispatch(new AproveFixerMan($request->fixerman_id));
        $user = User::where('id',$request->fixerman_id)->first();
        return $user->sendNotification($user);
    }

    public function testWorker(){
        // $this->register();
       $user = User::with('sendNotification')->get();
       return $user;

    }
}
