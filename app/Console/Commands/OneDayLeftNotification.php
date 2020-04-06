<?php

namespace App\Console\Commands;

use DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Notifications\Database\OneDayLeftNotification as DatabaseOneDayLeftNotification;

class OneDayLeftNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixerman:onedayleft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificará al técnico un día antes del servicio';

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
        $mañana = Carbon::now()->addDay()->format('Y/m/d');
        $orders = DB::table('selected_orders as s')->join('orders as o','o.id','s.order_id')->join('users as u','s.user_id','u.id')
        ->select('o.id','u.id as id_user')
        ->whereDate('o.service_date',$mañana)->get();
        Log::notice($orders);
        foreach ($orders as $key) {
            $fixerman = User::where('id',$key->id_user)->first();
            $key->mensajeFixerMan = "Mañana tienes una orden de servicio";
            $fixerman->notify(new DatabaseOneDayLeftNotification($key));
            $notification = $fixerman->notifications()->first();
            $key->created_at = $notification->created_at;
            Log::notice($notification);

            $fixerman->sendNotification($fixerman->email,"OneDayLeftNotification",$key);
        }
    }
}
