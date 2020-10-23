<?php

namespace App\Jobs\Mail;

use Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UserConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $usuario = array('lastName' => $this->user["lastName"], 'name' => $this->user["name"]);
        // $mail = $this->user["email"];
        $mail = "kliphome97@gmail.com";
        Mail::send('emails.emailconfirmation',$usuario, function($msj) use ($mail){
            $msj->subject('KlipHome: Un nuevo usuario ha terminado el registro');
            $msj->to($mail,"Nuevo Usuario Registrado");
        });
    }
}
