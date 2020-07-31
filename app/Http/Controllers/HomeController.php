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
        return view('admin.admin',compact('clientes','tecnicos','pagos','ordenes','categories'));
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
        $categories = Category::all();
        $ordenes = Order::where('state','!=','CANCELLED')->get();
        for ($i=0; $i < count($ordenes); $i++) {
            $category = $this->getCategory($ordenes[$i]["type_service"],$ordenes[$i]["selected_id"]);
            for ($j=0; $j < count($categories); $j++) {
                if($categories[$j]->title == $category){
                    $categories[$j]["count"]  += 1;
                }
            }
        }
        return $categories;
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
                # code...
                break;
        }
    }

}
