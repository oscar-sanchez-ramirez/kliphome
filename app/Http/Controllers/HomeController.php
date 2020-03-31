<?php

namespace App\Http\Controllers;
use App\User;
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

    public function prueba_pago(){
        Stripe\Stripe::setApiKey("sk_test_f2VYH7q0KzFbrTeZfSvSsE8R00VBDQGTPN");
        $pago = Stripe\Charge::create ([
            "amount" => 50 * 100,
            "currency" => "MXN",
            "card" => "card_1GSQuMCsoMg3FOVWnugt0zkh",
            "description" => "Pago por visita"
        ]);
        return $pago;
    }

}
