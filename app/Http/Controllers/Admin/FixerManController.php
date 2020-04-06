<?php

namespace App\Http\Controllers\Admin;

use DB;
use Image;
use App\User;
use App\Order;
use App\Category;
use Carbon\Carbon;
use App\SelectedOrders;
use Illuminate\Http\Request;
use App\Jobs\AproveFixerMan;
use App\Http\Controllers\Controller;
use App\Notifications\Database\ManualSelectedOrder;
use App\Notifications\Database\DisapproveOrderFixerMan as DatabaseDisapproveOrderFixerMan;
use App\Notifications\Database\ApproveOrderFixerMan as DatabaseApproveOrderFixerMan;


class FixerManController extends Controller
{
    public function __construct(){
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
        $delegation = DB::table('selected_delegations')->select('municipio as title','postal_code')->where('user_id',$id)->get();
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
        $order = Order::where('id',$id_orden)->first();
        $fixerman = User::where('id',$id_tecnico)->first();
        $date = Carbon::createFromFormat('Y/m/d H:i', $order->service_date);
        $user_order = User::where('id',$order->user_id)->first();
        $new_selected = new SelectedOrders;
        $new_selected->user_id = $fixerman->id;
        $new_selected->order_id = $id_orden;
        $new_selected->state = 1;
        $new_selected->save();
        $otherRequest = DB::table('selected_orders')->where('user_id','!=',$fixerman->id)->where('order_id',$order->id)->get();
        if(!empty($otherRequest)){
            $order["mensajeClient"] = ucwords(strtolower($user_order->name))." ya asignó su trabajo con otro técnico";
            $order["mensajeFixerMan"] = ucwords(strtolower($user_order->name))." ya asignó su trabajo con otro técnico";
            foreach ($otherRequest as $key) {
                $notFixerman = User::where('id',$key->user_id)->first();
                $notFixerman->sendNotification($fixerman->email,'DisapproveOrderFixerMan');
                $notFixerman->notify(new DatabaseDisapproveOrderFixerMan($order));
            }
            DB::table('selected_orders')->where('user_id','!=',$fixerman->id)->where('order_id',$order->id)->update([
                'state' => 0
            ]);
        }
        //Notification for Fixerman
        $order["mensajeClient"] = "¡Listo! Se ha Confirmado tu trabajo con ".$fixerman->name." para el día ".Carbon::parse($date)->format('d,M H:i');
        $order["mensajeFixerMan"] = "KlipHome ha  confirmado tu trabajo con ".$user_order->name." para el día ".Carbon::parse($date)->format('d,M H:i');
        $fixerman->notify(new DatabaseApproveOrderFixerMan($order,$fixerman->email));
        $user_order->notify(new ManualSelectedOrder($order,$user_order->email));
        Order::where('id',$id_orden)->update([
            'state' => 'FIXERMAN_APPROVED'
        ]);
        return back()->with('success','Se asignó al técnico');
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
