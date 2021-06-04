<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use OneSignal;
use Carbon\Carbon;
use App\AdminCoupon;
use App\TempPayment;
use Illuminate\Http\Request;

class WebController extends ControllerWeb
{
    public function terminos(){
        return view('terminos');
    }
    public function email_verified($code){
        $user = User::where('code',$code)->where('email_verified_at',null)->first();
        if($user){
            User::where('code',$code)->update([
                'email_verified_at' => Carbon::now()
            ]);
            return view('emailverified');
        }else{
            return view('emailnotverified');
        }
    }
    public function test(){
        // $alert = array('alert' => "Una nueva orden fue creada");
        // $mail = "germanruelas17@gmail.com";
        // Mail::send('emails.alert',$alert, function($msj) use ($mail){
        //     $msj->subject('KlipHome: Nueva orden');
        //     $msj->to($mail,"Detalle");
        // });
        // $content = User::where('email','germanruelas17@gmail.com')->first();
        // OneSignal::sendNotificationUsingTags(
        //     'Probando push notification',
        //     array(
        //         ["field" => "tag", "key" => "email",'relation'=> "=", "value" => 'germanruelas17@gmail.com'],
        //     ),
        //     $type=null,
        //     $content,
        //     $url=null,
        //     $data = null,
        //     $buttons = null,
        //     $schedule = null
        // );
    }
}
