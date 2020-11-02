<?php

namespace App\Jobs\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ConektaError implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $name;
    protected $tipo;
    protected $error;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name,$tipo,$error)
    {
        $this->name = $name;
        $this->tipo = $tipo;
        $this->error = $error;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $usuario = array('name' => $this->name, 'tipo' => $this->tipo["name"],'error'=> $this->error);
        // $mail = "tonyhamui68@gmail.com";
        $mail = "germanruelas17@gmail.com";
        Mail::send('emails.conektaerror',$usuario, function($msj) use ($mail){
            $msj->subject('KlipHome: Error en conekta');
            $msj->to($mail,"Datos del error Conekta");
        });
    }
}
