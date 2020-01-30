<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OneSignal;
use DB;
use App\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\Database\ApproveOrderFixerMan as DatabaseApproveOrderFixerMan;
use App\Notifications\Database\DisapproveOrderFixerMan as DatabaseDisapproveOrderFixerMan;

class ApproveOrderFixerMan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $fixerman_id;
    protected $order_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fixerman_id,$order_id)
    {
        $this->fixerman_id = $fixerman_id;
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Notification for Client
        $order = Order::where('id',$this->order_id)->first();
        $fixerman = User::where('id',$this->fixerman_id)->first();
        $date = Carbon::createFromFormat('d/m/Y H:i', $order->service_date);
        $user_order = User::where('id',$order->user_id)->first();

        $otherRequest = DB::table('selected_orders')->where('user_id','!=',$fixerman->id)->where('order_id',$order->id)->get();
        if(!empty($otherRequest)){
            $order["mensajeClient"] = ucwords(strtolower($user_order->name))." ya asignó su trabajo con otro técnico";
            $order["mensajeFixerMan"] = ucwords(strtolower($user_order->name))." ya asignó su trabajo con otro técnico";
            foreach ($otherRequest as $key) {
                $notFixerman = User::where('user_id',$key->user_id)->first();
                $notFixerman->sendNotification($fixerman->email,'DisapproveOrderFixerMan');
                $notFixerman->notify(new DatabaseDisapproveOrderFixerMan($order));
            }
            DB::table('selected_orders')->where('user_id','!=',$fixerman->id)->where('order_id',$order->id)->update([
                'state' => 0
            ]);
        }

        //Notification for Fixerman
        $order["mensajeClient"] = "¡Listo! Se ha Confirmado tu trabajo con ".$fixerman->name." para el día ".Carbon::parse($date)->format('d,M H:i');
        $order["mensajeFixerMan"] = "¡Listo! Se ha Confirmado tu trabajo con ".$user_order->name." para el día ".Carbon::parse($date)->format('d,M H:i');
        $fixerman->sendNotification($fixerman->email,'ApproveOrderFixerMan');
        $fixerman->notify(new DatabaseApproveOrderFixerMan($order));
        //
        Order::where('id',$this->order_id)->update([
            'state' => 'FIXERMAN_APPROVED'
        ]);
    }
}
