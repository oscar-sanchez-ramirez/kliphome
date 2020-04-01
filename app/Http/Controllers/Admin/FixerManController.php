<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\AproveFixerMan;
use App\User;
use App\Category;
use DB;
use Image;
use Carbon\Carbon;


class FixerManController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
        if($request->filled('notification_id')){
            DB::table('notifications')->where('type',"App\Notifications\NewFixerMan")->update(['read_at'=>Carbon::now()]);
        }
        $users = User::where('type','AppFixerMan')->get();
        return view('admin.fixerman.index')->with('users',$users);
    }
    public function detail($id){
        $delegation = DB::table('selected_delegations')->select('colony as title','postal_code')->where('user_id',$id)->get();
        $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$id)->get();
        return response()->json([
            'delegations' => $delegation,
            'categories' => $categories
        ]);
    }
    public function list(){
        $categories = Category::all();
        $fixerman = User::where('type',"AppFixerMan")->where('state',1)->with('categories')->get();
        return response()->json([
            'fixerman' => $fixerman,
            'categories' => $categories
        ]);
    }
    public function asignarTecnico($id_tecnico,$id_orden){

    }
    public function aprove(Request $request){
        User::where('id',$request->fixerman_id)->update([
            'state' => true
        ]);
        dispatch(new AproveFixerMan($request->fixerman_id));
    }

    public function updateFixermanImage(Request $request){
        $idFixerman = $request->idFixerman;
        $file = $request->file('imagen');
        $random = str_random(15);
        $nombre = trim('images/'.$random.".png");
        $image = Image::make($file->getRealPath())->resize(200, 240);
        // ->orientate()
        $image->save($nombre);

        User::where('id',$request->idFixerman)->update([
            'avatar' => $request->url.'/'.$nombre
        ]);
        return back()->with('success',"La imagen se actualizó");
    }
}
