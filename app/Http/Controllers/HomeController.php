<?php

namespace App\Http\Controllers;
use App\Order;
use App\User;
use App\Payment;
use Carbon\Carbon;
use DB;
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
        return view('admin.admin',compact('clientes','tecnicos','pagos','ordenes'));
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

}
