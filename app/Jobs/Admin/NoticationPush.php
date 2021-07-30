<?php

namespace App\Jobs\Admin;

use App\Order;
use App\User;
use OneSignal;
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
    protected $segmento;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($clientes,$tecnicos,$mensaje,$segmento)
    {
        $this->clientes = $clientes;
        $this->tecnicos = $tecnicos;
        $this->mensaje = $mensaje;
        $this->segmento = $segmento;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->clientes != ""){
            switch ($this->segmento) {
                case 'todos':
                    $clientes = User::where('type','AppUser')->where('state',1)->get();
                    break;
                case 'sin_registro':
                    $clientes = "sin_registro";
                    break;
                case 'con_orden':
                    $usuarios = Order::pluck('user_id');
                    $clientes =User::where('type','AppUser')->whereIn('id',$usuarios)->orderBy('id',"DESC")->get();
                    break;
                case 'sin_orden':
                    $usuarios = Order::pluck('user_id');
                    $clientes = User::where('type','AppUser')->whereNotIn('id',$usuarios)->orderBy('id',"DESC")->get();
                    break;
                default:
                    # code...
                    break;
            }
            if($clientes == "sin_registro"){
                $content = '';
                OneSignal::sendNotificationUsingTags(
                    $this->mensaje,
                    array(
                        ["field" => "tag", "key" => "email",'relation'=> "=", "value" => "descarga@kliphome.com"],
                    ),
                    $type=null,
                    $content,
                    $url=null,
                    $data = null,
                    $buttons = null,
                    $schedule = null
                );
            }else{
                foreach ($clientes as $key => $cliente) {
                    $cliente["mensajeClient"] = $this->mensaje;
                    $cliente->notify(new NotificationNoticationPush($cliente,$this->mensaje));
                }
            }
        }
        if($this->tecnicos != ""){
            $tecnicos = User::where('type','AppFixerMan')->where('state',1)->get();
            foreach ($tecnicos as $key => $tecnico) {
                $tecnico["mensajeFixerMan"] = $this->mensaje;
                $tecnico->sendNotification($tecnico->email,'NotificationPush',$tecnico);
                $tecnico->notify(new NotificationNoticationPush($tecnico,$this->mensaje));
            }

        }
    }
}
