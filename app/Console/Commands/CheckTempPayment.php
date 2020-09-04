<?php

namespace App\Console\Commands;

use Mail;
use App\TempPayment;
use Illuminate\Console\Command;

class CheckTempPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisara si hay pagos en pagos temporales para revisar';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pagos = TempPayment::all();
        if(count($pagos) > 0){
            // ,"adrimabarak@hotmail.com"
            $usuarios = ["germanruelas17@gmail.com"];
            for ($i=0; $i < count($usuarios); $i++) {
                # code...
                $html = array('alert'=>'Hay pagos temporales sin asignar a una orden');
                $mail = $usuarios[$i];
                Mail::send('emails.alert',$html, function($msj) use ($mail){
                    $msj->subject('KlipHome: Revisar pagos');
                    $msj->to($mail,"Pagos pendientes");
                });
            }
        }
    }
}
