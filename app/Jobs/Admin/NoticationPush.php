<?php

namespace App\Jobs\Admin;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Admin\NoticationPush as NotificationNoticationPush;

class NoticationPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clientes;
    protected $tecnicos;
    protected $mensaje;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($clientes,$tecnicos,$mensaje)
    {
        $this->clientes = $clientes;
        $this->tecnicos = $tecnicos;
        $this->mensaje = $mensaje;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->clientes != ""){
            $clientes = User::where('type','AppUser')->where('state',1)->whereIn('email',["germanruelas17@gmail.com","adrimabarak@hotmail.com"])->get();
            foreach ($clientes as $key => $cliente) {
                $cliente["mensajeClient"] = $this->mensaje;
                $cliente->notify(new NotificationNoticationPush($cliente,$this->mensaje));
            }
        }
        if($this->tecnicos != ""){
            $tecnicos = User::where('type','AppFixerMan')->where('state',1)->where('email',"jose@gmail.com")->get();
            foreach ($tecnicos as $key => $tecnico) {
                $tecnico["mensajeFixerMan"] = $this->mensaje;
                $tecnico->sendNotification($tecnico->email,'NotificationPush',$tecnico);
                $tecnico->notify(new NotificationNoticationPush($tecnico,$this->mensaje));
            }

        }
    }
}
