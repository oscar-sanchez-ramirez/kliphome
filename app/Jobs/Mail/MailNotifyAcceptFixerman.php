<?php

namespace App\Jobs\Mail;

use Mail;
use App\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MailNotifyAcceptFixerman implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $fixerman_mail;
    protected $name;
    protected $avatar;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fixerman_mail,$name,$avatar)
    {
        $this->fixerman_mail = $fixerman_mail;
        $this->name = $name;
        $this->avatar = $avatar;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $usuario = array('service_image'=>$this->avatar,'name'=>$this->name);
        $mail = $this->fixerman_mail;
        Mail::send('emails.fixermanAccepted',$usuario, function($msj) use ($mail){
            $msj->subject('KlipHome: Bienvenido a la comunidad Klip');
            $msj->to($mail,"Tu registro fue aprobado");
        });
    }
}
