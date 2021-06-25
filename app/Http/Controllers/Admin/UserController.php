<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\User;
use App\Order;
use App\Address;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index(Request $request){
        if(\request()->ajax()){
            if($request->filled('chart_query')){
                $monthlysales=$this->cast_date(DB::table('users')->select(DB::raw('count(id) as total'),DB::raw('date(created_at) as dates'))
                ->whereBetween(DB::raw('DATE(created_at)'), array($request->start, $request->end))
                ->where('type','AppUser')->groupBy('dates')->orderBy('dates','asc')->get());
                return $monthlysales;
            }else{
                $key = $request->get('query');
                $users = User::where('name','LIKE','%'.$key.'%')->orWhere('lastName','LIKE','%'.$key.'%')->where('type','AppUser')->withCount('orders')->with('address')->get();
                return $users;
            }
        }else{
            if($request->filled('filtro')){
                $users = $this->filtro($request->filtro);
            }else{
                $users = User::where('type','AppUser')->orderBy('id',"DESC")->withCount('orders')->paginate(10);
            }
            // return $users;
            $monthlysales=$this->cast_date(DB::table('users')->select(DB::raw('count(id) as total'),DB::raw('date(created_at) as dates'))->where('type','AppUser')->groupBy('dates')->orderBy('dates','asc')->get());
            return view('admin.users.index',compact('users','monthlysales'));
        }
    }
    public function busqueda(Request $request){
        $key = $request->keywords;
        $users = User::where('type','AppUser')->where('name','LIKE','%'.$key.'%')->orWhere('lastName','LIKE','%'.$key.'%')->where('state',1)->withCount('orders')->get();
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
    public function busqueda_tecnico(Request $request){
        $key = $request->keywords;
        $users = User::where('type','AppFixerMan')->where('name','LIKE','%'.$key.'%')->orWhere('lastName','LIKE','%'.$key.'%')->get();
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
    private function cast_date($users){
        $months = [];
        for ($i=0; $i < count($users); $i++) {
            $date = substr($users[$i]->dates,-16,10);
            if(array_search($date,array_column($months,"x"))){
                $index = array_search($date,array_column($months,"x"));
                $months[$index]["y"] = $months[$index]["y"] + $users[$i]->total;
            }else{
                array_push($months,array("x" => $date,"y"=>$users[$i]->total));
            }
        }
        return $months;
    }

    public function export(Request $request){
        if($request->filtro == ''){
            return back();
        }
        return Excel::download(new UsersExport($request->filtro), 'usuarios.xlsx');
    }

    private function filtro($key){
        switch ($key) {
            case 'con_orden':
                $usuarios = Order::pluck('user_id');
                return User::where('type','AppUser')->whereIn('id',$usuarios)->orderBy('id',"DESC")->withCount('orders')->get();
            case 'sin_orden':
                $usuarios = Order::pluck('user_id');
                return User::where('type','AppUser')->whereNotIn('id',$usuarios)->orderBy('id',"DESC")->withCount('orders')->get();
            case 'todos':
                return User::where('type','AppUser')->orderBy('id',"DESC")->withCount('orders')->paginate(10);
            default:
                # code...
                break;
        }
    }
    public function nueva_direccion(Request $request){
        $add = new Address;
        $add->alias = $request->alias;
        $add->street = $request->street;
        $add->reference = $request->reference;
        $add->postal_code = $request->postal_code;
        $add->colonia = $request->colonia;
        $add->municipio = $request->municipio;
        $add->exterior = $request->exterior;
        $add->interior = $request->interior;
        $add->user_id = $request->user_id;
        $add->delegation = "-";
        $add->save();

        $address = Address::where('user_id',$request->user_id)->get();
        return response()->json([
            'address' => $address
        ]);
    }
}
