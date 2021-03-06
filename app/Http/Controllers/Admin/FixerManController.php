<?php

namespace App\Http\Controllers\Admin;

use DB;
use Image;
use App\User;
use App\Order;
use App\Payment;
use App\Category;
use Carbon\Carbon;
use App\FixermanGallery;
use App\SelectedOrders;
use App\SelectedCategories;
use Illuminate\Http\Request;
use App\Jobs\AproveFixerMan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
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
        if(\request()->ajax()){
            if($request->filled('chart_query')){
                $monthlysales=$this->cast_date(DB::table('users')->select(DB::raw('count(id) as total'),DB::raw('date(created_at) as dates'))
                ->whereBetween(DB::raw('DATE(created_at)'), array($request->start, $request->end))
                ->where('type','AppFixerMan')->groupBy('dates')->orderBy('dates','asc')->get());
                return $monthlysales;
            }else{
                $key = $request->get('query');
                if($key == ''){
                    $fixerman = User::where('type','AppFixerMan')->where('state',1)->with('categories')->get();
                }else{

                    $fixerman = User::where('type','AppFixerMan')->where('state',1)->where('name','LIKE','%'.$key.'%')->orWhere('lastName','LIKE','%'.$key.'%')->with('categories')->get();
                }
                return response()->json([
                    'fixerman' => $fixerman
                ]);
            }
        }
        $users = User::where('type','AppFixerMan')->orderBy('id','desc')->paginate(10);
        $categories = Category::all();
        $monthlysales=$this->cast_date(DB::table('users')->select(DB::raw('count(id) as total'),DB::raw('date(created_at) as dates'))->where('type','AppFixerMan')->groupBy('dates')->orderBy('dates','asc')->get());
        return view('admin.fixerman.index',compact('users','monthlysales','categories'));
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
    public function detail($id){
        $ficha = DB::table('fixerman_stats')->where('user_id',$id)->first();
        $delegation = DB::table('selected_delegations')->select('municipio as title','postal_code')->where('user_id',$id)->get();
        $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$id)->get();
        $reviews = DB::table('qualifies as q')->join('selected_orders as so','so.id','q.selected_order_id')->join('orders as o','o.id','so.order_id')->join('users as u','u.id','o.user_id')
        ->select('q.*','u.avatar','u.name','u.lastName')->where('q.user_id',$id)->orderBy('q.created_at','DESC')->get();
        $payments = DB::table('selected_orders as so')->join('fixerman_stats as ft','ft.user_id','so.user_id')->join('orders as o','o.id','so.order_id')->join('payments as p','p.order_id','o.id')->leftJoin('quotations as q','o.id','q.order_id')
        ->select('p.*','q.workforce','q.price as service_price','ft.percent')->where('so.user_id',$id)->where('so.state',1)->get();
        return response()->json([
            'delegations' => $delegation,
            'categories' => $categories,
            'ficha' => $ficha,
            'reviews' => $reviews,
            'payments' => $payments
        ]);
    }

    public function detail1($id){
        $fixerman = User::where('id',$id)->with('gallery')->first();
        $ficha = json_encode(DB::table('fixerman_stats')->where('user_id',$id)->first());
        $delegation = DB::table('selected_delegations')->select('municipio as title','postal_code')->where('user_id',$id)->get();
        $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$id)->get();

        return view('admin.fixerman.detail')->with('fixerman',$fixerman)->with('ficha',$ficha)->with('delegation',$delegation)->with('categories',$categories);
    }
    public function reviews($id){
        $reviews = DB::table('qualifies as q')->join('selected_orders as so','so.id','q.selected_order_id')->join('orders as o','o.id','so.order_id')->join('users as u','u.id','o.user_id')
        ->select('q.*','u.avatar','u.name','u.lastName')->where('q.user_id',$id)->orderBy('q.created_at','DESC')->get();
        return response()->json([
            'reviews' => $reviews
        ]);
    }
    public function payments($id){
        $payments = DB::table('selected_orders as so')->join('fixerman_stats as ft','ft.user_id','so.user_id')->join('orders as o','o.id','so.order_id')->join('payments as p','p.order_id','o.id')->leftJoin('quotations as q','o.id','q.order_id')
        ->select('p.*','q.workforce','q.price as service_price','ft.percent')->where('so.user_id',$id)->where('so.state',1)->get();
        return response()->json([
            'payments' => $payments
        ]);
    }
    public function guardar_ficha(Request $request){
        DB::table('fixerman_stats')->where('user_id',$request->fixerman_id)->update([
            'acuerdo_laboral'=>$request->acuerdo_laboral,
            'prueba_psicologica'=>$request->prueba_psicologica,
            'comprobante_domicilio'=>$request->comprobante_domicilio,
            'asistencia_entrevista'=>$request->asistencia_entrevista,
            'copia_dni'=>$request->copia_dni,
            'foto'=>$request->foto,
            'kit_bienvenida'=>$request->kit_bienvenida,
            'percent' => $request->percent
        ]);
        return response()->json([
            'success' => true
        ]);
    }
    public function guardar_imagen_dato(Request $request,$id){
        $file = $request->file('imagen');
        $random = str_random(15);
        $nombre = trim('images/'.$random.".png");
        $image = Image::make($file->getRealPath())->resize(200, 240);
        // ->orientate()
        $image->save($nombre);
        $tipo = DB::table('fixerman_galleries')->where('user_id',$id)->where('type',$request->tipo)->first();
        if($tipo){
            FixermanGallery::where('user_id',$id)->where('type',$request->tipo)->update([
                'path' => url('').'/'.$nombre
            ]);
        }else{
            FixermanGallery::create([
                'user_id' => $id,
                'type' => $request->tipo,
                'path' => url('').'/'.$nombre
            ]);
        }
        return response()->json([
            'fixerman' => User::where('id',$id)->with('gallery')->first(),
            'success' => true
        ]);
    }
    public function guardar_datos($id,Request $request){
        if($request->state == "true"){
            $state = "1";
        }else{
            $state = "0";
        }
        User::where('id',$id)->update([
            'name' => $request->name,
            'lastName' => $request->lastName,
            'phone' => $request->phone,
            'email' => $request->email,
            'code' => $request->code,
            'state' => $state
        ]);
        return response()->json([
            'success' => true,
            'res' => $request->all()
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
    public function eliminarTecnico($id_tecnico,$id_orden){
        SelectedOrders::where('order_id',$id_orden)->where('user_id',$id_tecnico)->delete();
        return response()->json([
            'success' => true
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
        //Notification for Fixerman
        $order["mensajeClient"] = "??Listo! Se ha Confirmado tu trabajo con ".$fixerman->name." para el d??a ".Carbon::parse($date)->format('d,M H:i');
        $order["mensajeFixerMan"] = "KlipHome ha  confirmado tu trabajo con ".$user_order->name." para el d??a ".Carbon::parse($date)->format('d,M H:i');
        $order["type"] = "App\Notifications\Database\ApproveOrderFixerMan";
        $fixerman->notify(new DatabaseApproveOrderFixerMan($order,$fixerman->email));
        $user_order->notify(new ManualSelectedOrder($order,$user_order->email));
        Order::where('id',$id_orden)->update([
            'state' => 'FIXERMAN_APPROVED'
        ]);
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
        return back()->with('success',"La imagen se actualiz??");
    }
    public function filtro(Request $request){
        $selected_categories = SelectedCategories::where('category_id',$request->category)->pluck('user_id');
        return User::whereIn('id',$selected_categories)->orderBy('id','desc')->get();
    }
    // axios
    public function orders($user_id){
        $final_orders = [];
        $orders = DB::table('orders as o')
        ->join('users as u','u.id','o.user_id')
        ->join('selected_orders as so','o.id','so.order_id')
        ->where('so.user_id',$user_id)->where('so.state',1)
        ->select('o.*','u.name','u.lastName','u.avatar','so.id as idOrderAccepted','so.created_at as orderAcepted','q.workforce')
        ->distinct(['o.id','q.order_id'])
        // ->groupBy('q.order_id')
        ->leftJoin('quotations as q','q.order_id','o.id')
        ->get();

        return $orders;
    }
    public function calcular(Request $request){
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d H:i:i');
        $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d H:i:i');
        $orders = DB::table('orders as o')
        ->join('selected_orders as so','o.id','so.order_id')->select('o.*')
        ->whereDate('o.service_date','>=',$fecha_inicio)->whereDate('o.service_date','<=',$fecha_fin)->where('so.user_id',$request->id_fixerman)->where('so.state',1)
        ->get();
        $ids = array_column($orders->toArray(), 'id');
        $servicios = 0;
        $propinas = 0;
        $percent = 100;
        $visita = 0;
        if($request->porcentaje != null && $request->porcentaje != "false"){
            $stats = DB::table('fixerman_stats')->where('user_id',$request->id_fixerman)->first();
            $percent = $stats->percent;
        }
        if($request->precio_propina != null && $request->precio_propina != "false"){
            $propinas = Payment::where('description','PROPINA POR SERVICIO')->where('state',1)->whereIn('order_id',$ids)->sum('price');
        }
        if($request->precio_servicio != null && $request->precio_servicio != "false"){
            $servicios = DB::table('orders as o')
            ->join('payments as p','o.id','p.order_id')
            ->join('quotations as q','q.order_id','o.id')
            ->where('description','PAGO POR SERVICIO')->where('p.state',1)->whereIn('o.id',$ids)->sum('q.workforce');
            $servicios = ($servicios * $percent)/100;

        }
        if($request->precio_visita != null && $request->precio_visita != "false"){
            $visita = Payment::where('description','VISITA')->where('state',1)->whereIn('order_id',$ids)->sum('price');
            $visita = ($visita * $percent)/100;
        }
        return response()->json([
            'request' => $request->all(),
            'propinas' => $propinas,
            'visita' => $visita,
            'servicios' => $servicios
        ]);

    }
}
