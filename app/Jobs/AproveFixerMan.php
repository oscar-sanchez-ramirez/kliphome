<?php

namespace App\Jobs;

use App\User;
use Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AproveFixerMan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $mail = "germanruelas17@gmail.com";
        // $usuario = array('nombre_completo' =>  "German");

        // Mail::send('emails.aproveFixerMan',$usuario, function($msj) use ($mail){
        //     $msj->subject('Bienvenido a KlipHome');
        //     $msj->to($mail,"Empieza a disfrutar de nuestra grandiosa comunidad");
        // });
        $user = User::where('id',$user_id)->with('sendNotification')->first();
        return $user;
    }
}
