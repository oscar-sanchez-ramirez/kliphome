<?php

namespace App\Jobs\Mail;

use DB;
use Mail;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MailOrderAccepted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $order_id;
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = DB::table('orders as o')->join('users as u','o.user_id','u.id')->select('o.*','u.email')->where('o.id',$this->order_id)->first();
        $visita = Payment::where('order_id',$this->order_id)->where('description',"VISITA")->where('state',1)->first();
        $monto = Payment::where('order_id',$this->order_id)->where('description',"PAGO POR SERVICIO")->where('state',1)->first();
        $fecha = Carbon::createFromFormat('Y/m/d H:i', $order->service_date);
        $usuario = array('monto' => $monto->price, 'visita' => $visita->price,'fecha'=> $fecha->format('d/m/Y H:i'),'service_image'=>$order->service_image);
        $mail = $order->email;
        Mail::send('emails.neworder',$usuario, function($msj) use ($mail){
            $msj->subject('KlipHome: Tu orden de servicio fue procesado');
            $msj->to($mail,"Detalle");
        });
    }
}
