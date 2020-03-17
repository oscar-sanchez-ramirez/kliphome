<?php

namespace App\Http\Controllers\ApiRest;

use App\Address;
use App\ResetPassword;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\User;
use Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RegisterController extends ApiController
{
    public function register(ClientRequest $request){
        $random = strtoupper(substr(md5(mt_rand()), 0, 10));
        $user = User::create([
            'name' => $request->name,
            'lastName' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'code' => $random
        ])->toArray();
        $address = $request->delegation;

        // Test if string contains the word
        if((strpos($address, "Ciudad de Mexico") !== false) || strpos($address, "CDMX") || strpos($address, "Méx., México")){
            $delegation = "1";
        } elseif(strpos($address, "Guadalajara") !== false){
            $delegation = "2";
        }
        Address::create([
            'alias' => $request->alias,
            'address' => $request->address,
            'reference' => $request->reference,
            'postal_code' => $request->postal_code,
            'user_id' => $user["id"],
            'delegation' => $delegation,
            'longitud' => $request->longitud,
            'latitud' => $request->latitud,
            'colonia' => $request->colonia
        ]);
        return response()->json([
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
        // $address = $request->delegation;
        // if((strpos($address, "Ciudad de Mexico") !== false) || strpos($address, "CDMX") || strpos($address, "Méx., México")){
        //     $delegation = "1";
        // } elseif(strpos($address, "Guadalajara") !== false){
        //     $delegation = "2";
        // }
        Address::create([
            'user_id'=>$request->user_id,
            'alias' => $request->alias,
            'address' => $request->address,
            'reference' => $request->reference,
            'postal_code' => $request->postal_code,
            'delegation' => "-",
            'longitud' => $request->longitud,
            'latitud' => $request->latitud,
            'colonia' => $request->colonia
        ]);
        return response()->json([
            'message' => "Dirección creada"
        ]);
    }

    public function updateAddress(Request $request){
        // $address = $request->delegation;
        // if((strpos($address, "Ciudad de Mexico") !== false) || strpos($address, "CDMX") || strpos($address, "Méx., México")){
        //     $delegation = "1";
        // } elseif(strpos($address, "Guadalajara") !== false){
        //     $delegation = "2";
        // }
        Address::where('user_id',$request->user_id)->update([
            'alias' => $request->alias,
            'address' => $request->address,
            'reference' => $request->reference,
            'postal_code' => $request->postal_code,
            'user_id' => $request->user_id,
            'delegation' => "-",
            'longitud' => $request->longitud,
            'latitud' => $request->latitud,
            'colonia' => $request->colonia
        ]);
        return response()->json([
            'message' => "Dirección actualizada"
        ]);
    }

    public function verifyemail(Request $request){
        $validate = $request->validate([
            'email' => 'required|unique:users|max:255'
        ]);
        return response()->json([
            'success' => false,
            'message' => $validate
        ]);
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

        $validateCode = ResetPassword::where('email',$request->email)->where('code',$request->code)->first();
        if(empty($validateCode)){
            return response()->json([
                'success' => false,
                'message' => "Código no encontrado"
            ]);
        }else{
            $startTime = $validateCode->created_at;
            $finishTime = Carbon::now();
            $totalDuration = ($finishTime->diffInSeconds($startTime))/60;
            if($totalDuration > 5){
                return response()->json([
                    'success' => false,
                    'message' => "Código ingresado expiró"
                ]);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => "Código válido"
                ]);
            }
        }
    }
}
