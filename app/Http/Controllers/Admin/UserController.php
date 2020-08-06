<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index(Request $request){
        if(\request()->ajax()){
            $key = $request->get('query');
            $users = User::where('name','LIKE','%'.$key.'%')->orWhere('lastName','LIKE','%'.$key.'%')->where('type','AppUser')->get();
            return $users;
        }else{
            $monthlysales=$this->cast_date(DB::table('users')->select(DB::raw('count(id) as total'),DB::raw('date(created_at) as dates'))->where('type','AppUser')->groupBy('dates')->orderBy('dates','asc')->get());
            $users = User::where('type','AppUser')->orderBy('id',"DESC")->paginate(10);
            return view('admin.users.index',compact('users','monthlysales'));
        }
    }
    private function cast_date($users){
        $months = [];
        for ($i=0; $i < count($users); $i++) {
            $date = substr($users[$i]->dates,-16,7);
            if(array_search($date,array_column($months,"x"))){
                $index = array_search($date,array_column($months,"x"));
                $months[$index]["y"] = $months[$index]["y"] + $users[$i]->total;
            }else{
                array_push($months,array("x" => $date,"y"=>$users[$i]->total));
            }
        }
        return $months;
    }
}
