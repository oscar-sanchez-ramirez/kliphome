<?php

namespace App\Http\Controllers\ApiRest;

use App\Address;
use App\ResetPassword;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\User;
use Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RegisterController extends ApiController
{
    public function register(Request $request){
        $this->validate($request,[
            'email' => 'required|email|unique:users',
            'name' => 'required',
            // 'lastName' => 'required',
            'password' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'lastName' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'code' => str_random(10)
        ])->toArray();
        $word = "Ciudad de México";
        $address = $request->address;

        // Test if string contains the word
        if((strpos($address, "Ciudad de México") !== false) || strpos($address, "CDMX")){
            $delegation = "Ciudad de México";
        } elseif(strpos($address, "Guadalajara") !== false){
            echo "Word Not Found!";
            $delegation = "Guadalajara";
        }
        Address::create([
            'alias' => $request->alias,
            'address' => $request->address,
            'user_id' => $user["id"],
            'delegation' => $delegation
        ]);
        return response()->json([
            'message' => "Usuario creado correctamente",
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request){
        User::where('email',$request->email)->update([
            'password' => bcrypt($request->password)
        ]);
        return response()->json([
            'success' => true,
            'message' => "La contraseña se actualizó"
        ]);
    }

    public function reset(Request $request){
        Log::notice($request->all());
        $user = User::where('email',$request->email)->first();
        Log::notice($user);
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
        Log::notice($request->all());
        Log::info($request->email);
        $validateCode = ResetPassword::where('email',$request->email)->where('code',$request->code)->first();
        Log::notice($validateCode);
        if(empty($validateCode)){
            return response()->json([
                'success' => false,
                'message' => "Código no encontrado"
            ]);
        }else{
            $startTime = $validateCode->created_at;
            $finishTime = Carbon::now();

            $totalDuration = ($finishTime->diffInSeconds($startTime))/60;
            Log::notice($startTime);
            Log::notice($finishTime);
            Log::notice($totalDuration);
            return response()->json([
                'success' => true,
                'message' => "Código válido"
            ]);
        }
    }
}
