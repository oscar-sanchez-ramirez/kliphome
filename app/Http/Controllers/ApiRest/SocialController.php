<?php

namespace App\Http\Controllers\ApiRest;

use App\User;
use Socialite;
use Carbon\Carbon;
use App\ConfigSystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SocialController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api', ['only' => ['']]);
        \Conekta\Conekta::setApiKey(ConfigSystem::conekta_key);
        \Conekta\Conekta::setLocale('es');
    }
    public function facebook(Request $request) {
        try {
            $user = Socialite::driver('facebook')->userFromToken($request->access_token);
            if($user == null){
                return response()->json([
                    "success" => false,
                    "message" => 'No se encontro al usuario, inténte con otra cuenta'
                ]);
            }else{
                $user = $this->checkifexists($user,$request->provider,$request->register,$request->phone);
                return response()->json([
                    "success" => true,
                    "user" => $user
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => 'Hubo un error inesperado, inténtelo más tarde'
            ]);
        }
    }

    public function facebook_flutter(Request $request){
        $user = User::where('email',$request->email)->first();
        $tel = 'not_required';
        if(!$user){
            $random = strtoupper(substr(md5(mt_rand()), 0, 10));
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'avatar' => $request->avatar,
                'password' => bcrypt($random),
                'code' => $random,
                'provider' => $request->provider,
                'state' => 1,
                'email_verified_at' => Carbon::now()
            ])->toArray();
        }
        $user->avatar = $request->avatar;
        $user->save();
        if($user->phone == null || $user->phone == ''){$tel="required";}
        return response()->json([
            "success" => true,
            "user" => $user,
            'phone' => $tel
        ]);
    }

    public function google(Request $request){
        try {
            $user = Socialite::driver('google')->userFromToken($request->access_token);
            if($user == null){
                return response()->json([
                    "success" => false,
                    "message" => 'No se encontro al usuario, inténte con otra cuenta'
                ]);
            }else{
                $user = $this->checkifexists($user,$request->provider,$request->register,$request->phone);
                return response()->json([
                    "success" => true,
                    "user" => $user
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => 'Hubo un error inesperado, inténtelo más tarde'
            ]);
        }
    }

    public function checkifexists($user,$provider,$register,$phone){
        $check_user = User::where('email',$user->email)->first();
        if(!$check_user){
            if($register){
                $random = strtoupper(substr(md5(mt_rand()), 0, 10));
                $user = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $phone,
                    'password' => bcrypt($random),
                    'code' => $random,
                    'provider' => $provider,
                    'state' => 1,
                    'email_verified_at' => Carbon::now()
                ])->toArray();
                return $user;
            }else{
                return null;
            }
            // dispatch(new UserConfirmation($user));
        }else{
            return $check_user;
        }
    }
    public function gmail(){

    }
    public function conekta(Request $request){
        return view('payment.conekta');

        $user = $request->user();
        $customer = \Conekta\Customer::create(
            [
              'name'  => $user->name.' '.$user->lastName,
              'email' => $user->email,
              'phone' => $user->phone,
              'payment_sources' => [
                [
                  'token_id' => $request->token,
                  'type' => "card"
                ]
              ]
            ]
          );
        return response()->json([
            "success" => true,
            "usuario" => $customer
        ]);


        // return $customer;

        // $customer = \Conekta\Order::create([
        //     'currency' => 'MXN',
        //     'customer_info' => [
        //       'customer_id' => 'cus_2oCiz55vyWSUozDdF'
        //     ],
        //     'line_items' => [
        //       [
        //         'name' => 'Box of Cohiba S1s',
        //         'unit_price' => 35000,
        //         'quantity' => 1
        //       ]
        //     ],
        //     'charges' => [
        //       [
        //         'payment_method' => [
        //           'type' => 'default'
        //         ]
        //       ]
        //     ]
        //   ]);
      }
}
