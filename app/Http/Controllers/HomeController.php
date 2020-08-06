<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Order;
use App\Payment;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $pagos = Payment::where('state',1)->sum('price');
        $ordenes = Order::where('state','!=','CANCELLED')->count();
        $categories = $this->get_count_orders();
        $array_dates = $this->array_dates;
        return view('admin.admin',compact('clientes','tecnicos','pagos','ordenes','categories','array_dates'));
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
