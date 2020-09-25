<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Order;
use App\Payment;
use App\Category;
use Carbon\Carbon;
use App\Quotation;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\PaymentController;

class HomeController extends Controller
{
    private $array_dates;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function admin(){

        $clientes = User::where('type','AppUser')->where('state',1)->count();
        $tecnicos = User::where('type','AppFixerMan')->where('state',1)->count();
        $ordenes = Order::where('state','!=','CANCELLED')->count();

        $payment_controller = new PaymentController();
        $total = [];
        $visitas = Payment::where('state',1)->where('description','VISITA')->get();

        $servicios = Payment::where('state',1)->where('description','PAGO POR SERVICIO')->orderBy('order_id')->get();
        foreach ($servicios as $key => $servicio) {
            if($servicio->code_payment == "EFECTIVO"){
                $visita = $visitas->filter(function($item) use ($servicio){
                    return $item->order_id === $servicio->order_id;
                })->first();
                if($visita){
                    $servicio->price = intval($servicio->price) - intval($visita->price);
                }
                array_push($total,$servicio);
            }else{
                $cotizaciones = Quotation::where('order_id',$servicio->order_id)->where('state',1)->get();
                if(count($cotizaciones) > 0){
                    if(count($cotizaciones) == 1){
                        $servicio["workforce"] = $cotizaciones[0]->workforce;
                        $servicio["service_price"] = $cotizaciones[0]->price;
                        array_push($total,$servicio);
                    }else{
                        foreach ($cotizaciones as $key => $cotizacion) {
                            $visita = $visitas->filter(function($item) use ($servicio){
                                return $item->order_id === $servicio->order_id;
                            })->first();
                            // if(!$visita){
                            //     $visita = collect(['price'=>0]);
                            // }
                            // if(!isset($servicio->price)){
                            //     $servicio->price = 0;
                            // }
                            if(!$visita){
                                if((intval($cotizacion->price) + intval($cotizacion->workforce)) - 0 == $servicio->price){
                                    $servicio["workforce"] = $cotizacion->workforce;
                                    $servicio["service_price"] = $cotizacion->price;
                                    array_push($total,$servicio);
                                }
                            }else{
                                if((intval($cotizacion->price) + intval($cotizacion->workforce)) - intval($visita->price) == $servicio->price){
                                    $servicio["workforce"] = $cotizacion->workforce;
                                    $servicio["service_price"] = $cotizacion->price;
                                    array_push($total,$servicio);
                                }
                            }
                            if(intval($cotizacion->price) + intval($cotizacion->workforce) == $servicio->price){
                                $servicio["workforce"] = $cotizacion->workforce;
                                $servicio["service_price"] = $cotizacion->price;
                                array_push($total,$servicio);
                            }
                        }
                    }
                }else{
                    return $servicio;
                }
            }
        }
        // return $servicios;
        $sum_payments = $payment_controller->stats($servicios,$visitas);
        $total_suma = [[ $visitas->sum('price')],$sum_payments[1]["total"],$sum_payments[0]["total_mano_de_obra"]];
        $categories = $this->get_count_orders();
        $array_dates = $this->array_dates;
         return view('admin.admin',compact('clientes','tecnicos','ordenes','categories','array_dates','sum_payments','total_suma'));
    }

    public function notificaciones(){
        $admin = User::where('type','ADMINISTRATOR')->first();
        $notificaciones = DB::table('notifications')->where('notifiable_id',$admin->id)->paginate(10);
        return view('admin.notifications.index')->with('notifications',$notificaciones);
    }

    public function markasread(){
        $admin = User::where('type','ADMINISTRATOR')->first();
        DB::table('notifications')->where('notifiable_id',$admin->id)->where('read_at',null)->update([
            'read_at' => Carbon::now()
        ]);
    }
    private function get_count_orders(){
        $categories = (Category::all());
        $ordenes = Order::where('state','!=','CANCELLED')->get();
        for ($i=0; $i < count($categories); $i++) {
            $this->array_dates[$i]["label"] = $categories[$i]["title"];
            $this->array_dates[$i]["showLine"] = true;
            $this->array_dates[$i]["fill"] = false;
            $random = rand(0, 255);
            $this->array_dates[$i]["borderColor"] = "rgba(".$random.",5,0, 0.3)";
            for ($j=0; $j <count($ordenes) ; $j++) {
                $category = $this->getCategory($ordenes[$j]["type_service"],$ordenes[$j]["selected_id"]);
                $date = $this->position($ordenes[$j]["service_date"]);
                if($categories[$i]->title == $category){
                    if(!isset($this->array_dates[$i]["data"])){
                        $this->array_dates[$i]["data"] = [];
                        array_push($this->array_dates[$i]["data"],array("x" => $date,"y"=>1));
                    }else{
                        if(array_search($date,array_column($this->array_dates[$i]["data"],"x"))){
                            $index = array_search($date,array_column($this->array_dates[$i]["data"],"x"));
                            $this->array_dates[$i]["data"][$index]["y"] += 1;
                        }else{
                            array_push($this->array_dates[$i]["data"],array("x" => $date,"y"=>1));
                        }
                    }
                    $categories[$i]["count"]  += 1;
                }
            }
        }
        // $this->sum_payments[0] = Payment::where('description','VISITA')->where('state',1)->sum('price');
        // $this->sum_payments[1] = DB::table('orders as o')->join('payments as p','p.order_id','o.id')->leftJoin('quotations as q','o.id','q.order_id')->leftJoin('selected_orders as so','o.id','so.order_id')->select('p.id,q.workforce','q.price as service_price')->distinct('p.id')->sum('q.workforce');
        // $this->sum_payments[2] = DB::table('orders as o')->join('payments as p','p.order_id','o.id')->leftJoin('quotations as q','o.id','q.order_id')->leftJoin('selected_orders as so','o.id','so.order_id')->select('p.id,q.workforce','q.price')->distinct('p.id')->sum('q.price');
        return $categories;
    }
    private function position($date){
        // $date = substr($date,-11,2);
        return $date = substr($date,-16,10);
        switch ($date) {
            case '01':
                return "Ene";
                break;
                case '02':
                    return "Feb";
                    break;
                    case '03':
                        return "Mar";
                        break;
                        case '04':
                            return "Abr";
                            break;
                            case '05':
                                return "May";
                                break;
                                case '06':
                                    return "Jun";
                                    break;
                                    case '07':
                                        return "Jul";
                                        break;
                                        case '08':
                                            return "Ago";
                                            break;
                                            case '09':
                                                return "Set";
                                                break;
                                                case '10':
                                                    return "Oct";
                                                    break;
                                                    case '11':
                                                        return "Nov";
                                                        break;
                                                        case '12':
                                                            return "Dic";
                                                            break;

            default:
                return "Ene";
                break;
        }
    }
    private function getCategory($type,$id){
        switch ($type) {
            case 'Category':
                $category = DB::table('categories')->select('title as category')->where('id',$id)->get();
                return $category[0]->category;
                break;
            case 'SubCategory':
                $category  = DB::table('sub_categories as su')->join('categories as ca','su.category_id','ca.id')->select('ca.title as category','su.title as sub_category')->where('su.id',$id)->get();
                return $category[0]->category;
                break;
            case 'Service':
                $category = DB::table('services as se')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('se.title as service','ca.title as category','su.title as sub_category')->where('se.id',$id)->get();
                return $category[0]->category;
                break;
            case 'SubService':
                $category = DB::table('sub_services as subse')->join('services as se','se.id','subse.service_id')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title as category','su.title as sub_category','subse.title as service','se.title as serviceTrait')->where('subse.id',$id)->get();
                return $category[0]->category;
                break;
            default:
                return "Ene";
                break;
        }
    }

}
