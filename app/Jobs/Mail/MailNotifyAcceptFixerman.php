<?php

namespace App\Jobs\Mail;

use Mail;
use App\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MailNotifyAcceptFixerman implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $order_id;
    protected $fixerman_mail;
    protected $name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id,$fixerman_mail,$name)
    {
        $this->order_id = $order_id;
        $this->fixerman_mail = $fixerman_mail;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = Order::where('id',$this->order_id)->first();
        $fecha = Carbon::createFromFormat('Y/m/d H:i', $order->service_date);
        $usuario = array('fecha'=> $fecha->format('d/m/Y H:i'),'service_image'=>$order->service_image,'name'=>$this->name);
        $mail = $this->fixerman_mail;
        Mail::send('emails.fixermanAccepted',$usuario, function($msj) use ($mail){
            $msj->subject('KlipHome: Tu order de servicio fue procesado');
            $msj->to($mail,"Detalle");
        });
    }
}
