<?php

namespace App\Http\Controllers\ApiRest;

use Mail;
use App\User;
use Validator;
use App\Address;
use Carbon\Carbon;
use App\ConfigSystem;
use App\ResetPassword;
use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;
use Illuminate\Support\Facades\Log;
use App\Jobs\Mail\UserConfirmation;
use App\Http\Requests\ClientRequest;
use App\Http\Controllers\ApiController;

class RegisterController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api', ['only' => ['newAddress','updateAddress']]);
    }
    public function register(ClientRequest $request){
        $random = strtoupper(substr(md5(mt_rand()), 0, 10));
        $user = User::create([
            'name' => $request->name,
            'lastName' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verified_at' => Carbon::now(),
            'code' => $random
        ])->toArray();

        if($request->street != '' && $request->alias != '' && $request->reference != '' && $request->postal_code != '' && $request->municipio != '' && $request->colony != ''){
            Address::create([
                'street' => $request->street,
                'alias' => $request->alias,
                'reference' => $request->reference,
                'postal_code' => $request->postal_code,
                'user_id' => $user["id"],
                'delegation' => "-",
                'exterior' => $request->exterior,
                'interior' => $request->interior,
                'colonia' => $request->colony,
                'municipio' => $request->municipio
            ]);
        }
        dispatch(new UserConfirmation($user));
        return response()->json([
            'success' => true,
            'message' => "Usuario creado correctamente",
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request){
        $validateCode = ResetPassword::where('email',$request->email)->where('code',$request->code)->first();
        $startTime = $validateCode->created_at;
        $finishTime = Carbon::now();
        $totalDuration = ($finishTime->diffInSeconds($startTime))/60;
        if($totalDuration > 5){
            return response()->json([
                'success' => false,
                'message' => "Código ingresado expiró"
            ]);
        }else{
            User::where('email',$request->email)->update([
                'password' => bcrypt($request->password)
            ]);
            ResetPassword::where('email',$request->email)->where('code',$request->code)->delete();
            return response()->json([
                'success' => true,
                'message' => "La contraseña se actualizó"
            ]);
        }

    }

    public function newAddress(Request $request){
        $user = $request->user();
        Address::create([
            'street' => $request->street,
            'alias' => $request->alias,
            'reference' => $request->reference,
            'postal_code' => $request->postal_code,
            'user_id'=>$user->id,
            'delegation' => "-",
            'exterior' => $request->exterior,
            'interior' => $request->interior,
            'colonia' => $request->colonia,
            'municipio' => $request->municipio
        ]);
        return response()->json([
            'message' => "Dirección creada"
        ]);
    }

    public function updateAddress(Request $request){
        Address::where('id',$request->id)->update([
            'street' => $request->street,
            'alias' => $request->alias,
            'reference' => $request->reference,
            'exterior' => $request->exterior,
            'interior' => $request->interior,
            'municipio' => $request->municipio,
            'colonia' => $request->colonia,
            'postal_code' => $request->postal_code
        ]);
        return response()->json([
            'message' => "Dirección actualizada"
        ]);
    }

    public function verifyphone(Request $request){
        // $num = (string)($request->phone);
        $num = substr((string)($request->phone), 2);
        $num = '521'.$num;

        // $sub = substr($num,0,1);
        // if($sub != "+"){
        //     $num = '+'.$num;
        // }

        $check = ResetPassword::where('email',$num)->count();
        if($check <= 3){
            $number = random_int(1000, 9999);
            $new_code = new ResetPassword;
            $new_code->email = $num;
            $new_code->code = $number;
            $new_code->save();

            Nexmo::message()->send([
                'to'   => $num,
                'from' => 'KlipHome',
                'text' => $number.' es tu numero de verificacion para KlipHome',
                'type' => 'text'
            ]);
            return response()->json([
                'success' => true,
                'message' => "El código se ha enviado a".$num,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Superaste el número de intentos por día"
            ]);
        }
    }

    public function verifyemail(Request $request){
        $v = Validator::make($request->all(), [
            'email' => 'unique:users|email',
        ]);
        if ($v->fails()){
            return response()->json([
                'success' => false,
                'message' => "Email ya esta en uso"
            ]);
        }else{
            // $number = random_int(1000, 9999);
            // $new_code = new ResetPassword;
            // $new_code->email = $request->email;
            // $new_code->code = $number;
            // $new_code->save();

            // $usuario = array('code' =>  $number, 'email' => $request->email);
            // $mail = $request->email;
            // Mail::send('emails.confirmemail',$usuario, function($msj) use ($mail){
            //     $msj->subject('KlipHome: Código para confirmar correo');
            //     $msj->to($mail,"KlipHome: Código para confirmar correo");
            // });

            return response()->json([
                'success' => true,
                'message' => "Email correcto"
            ]);
        }
    }

    public function check_social_account(){
        return $permisos = ConfigSystem::socialmedia;
    }

    public function reset(Request $request){
        $user = User::where('email',$request->email)->first();
        if(empty($user)){
            return response()->json([
                'success' => false,
                'message' => "Usuario no encontrado en nuestra base de datos"
            ]);
        }else{
            $number = random_int(1000, 9999);
            $new_code = new ResetPassword;
            $new_code->email = $user->email;
            $new_code->code = $number;
            $new_code->save();

            $usuario = array('code' =>  $number, 'email' => $user->email);
            $mail = $user->email;
            Mail::send('emails.resetpassword',$usuario, function($msj) use ($mail){
                $msj->subject('KlipHome: Código de acceso para reestablecer contraseña');
                $msj->to($mail,"KlipHome: Código de acceso para reestablecer contraseña");
            });

            return response()->json([
                'success' => true,
                'message' => "Se envió un codigo a tu correo"
            ]);
        }
    }

    public function validateCode(Request $request){

        if (str_contains($request->email, '@')) {
            $num1 = $request->email;
        }else{
            $num = substr((string)($request->email), 2);
            $num1 = '521'.$num;
        }

        $validateCode = ResetPassword::where('email',$num1)->where('code',$request->code)->first();
        if(empty($validateCode)){
            return response()->json([
                'success' => false,
                'message' => "Código no encontrado"
            ]);
        }else{
            // $startTime = $validateCode->created_at;
            // $finishTime = Carbon::now();
            // $totalDuration = ($finishTime->diffInSeconds($startTime))/60;
            // if($totalDuration > 10){
            //     return response()->json([
            //         'success' => false,
            //         'message' => "Código ingresado expiró"
            //     ]);
            // }else{
            // }
            return response()->json([
                'success' => true,
                'message' => "Código válido"
            ]);
        }
    }
}
