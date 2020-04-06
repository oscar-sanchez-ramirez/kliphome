<?php

namespace App\Console\Commands;

use DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Notifications\Database\FourHoursLeftNotification as DatabaseFourHoursLeftNotification;

class FourHoursLeftNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixerman:fourhoursleft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificará al técnico 4 horas antes del servicio';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hoy = Carbon::now()->format('Y/m/d');
        Log::notice($hoy);
        $orders = DB::table('selected_orders as s')->join('orders as o','o.id','s.order_id')->join('users as u','s.user_id','u.id')
        ->select('o.id','u.id as id_user','o.service_date')
        ->whereDate('o.service_date',$hoy)
        ->get();
        Log::notice($orders);
        foreach ($orders as $key) {
            $string = '{"id":'.$key->id.',"id_user":'.$key->id_user.',"service_date":"'.$key->service_date.'","mensajeFixerMan":"Ma\u00f1ana tienes una orden de servicio"}';
            Log::notice($string);
            $check = DB::table('notifications')->where('type','App\Notifications\Database\FourHoursLeftNotification')->where('data',preg_replace('/','\/',$string))->first();
            Log::notice($check);
            if(!$check){

                // $fecha_orden = Carbon::createFromFormat('Y/m/d H:i', $key->service_date);
                // $ahora = Carbon::now('America/Lima')->format('Y/m/d H:i');
                // $totalDuration = $fecha_orden->diffInSeconds($ahora);
                // Log::notice($totalDuration);
                // if(($totalDuration/60) > 0 && ($totalDuration/60) <= 240){
                //     $fixerman = User::where('id',$key->id_user)->first();
                //     Log::notice($fixerman);
                //     $key->mensajeFixerMan = "Mañana tienes una orden de servicio";
                //     $fixerman->notify(new DatabaseFourHoursLeftNotification($key,$fixerman->email));
                //     $notification = $fixerman->notifications()->first();
                //     $key->created_at = $notification->id;
                //     $fixerman->sendNotification($fixerman->email,"FourHoursLeftNotification",$key);
                // }
            }
        }
    }
}
