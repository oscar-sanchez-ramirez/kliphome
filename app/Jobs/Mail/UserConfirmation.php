<?php

namespace App\Jobs\Mail;

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
        $usuario = array('code' => $this->user["code"], 'name' => $this->user["name"]);
        $mail = $this->user["email"];
        Mail::send('emails.emailconfirmation',$usuario, function($msj) use ($mail){
            $msj->subject('KlipHome: Bienvenido a nuestra grandiosa comunidad');
            $msj->to($mail,"Verifica tu correo");
        });
    }
}
