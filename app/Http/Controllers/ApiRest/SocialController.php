<?php

namespace App\Http\Controllers\ApiRest;

use App\User;
use Socialite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SocialController extends ApiController
{
    public function __construct(){
        // $this->middleware('auth:api', ['only' => ['gmail','checkifexists']]);
        \Conekta\Conekta::setApiKey("key_UgnZqZxkdu5HBTHehznnbw");
        \Conekta\Conekta::setLocale('es');
    }
    public function facebook(Request $request) {
        try {
            Log::notice($request->all());
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
    public function conekta(){
        //
        $customer = \Conekta\Customer::create(
            [
              'name'  => "Mario Perez",
              'email' => "usuario@example.com",
              'phone' => "+5215555555555",
              'payment_sources' => [
                [
                  'token_id' => "tok_2oCiyRFUVUUNdQMS3",
                  'type' => "card"
                ]
              ],
              'shipping_contacts' => [
                [
                  'phone' => "+5215555555555",
                  'receiver' => "Marvin Fuller",
                  'address' => [
                    'street1' => "Nuevo Leon 4",
                    'street2' => "fake street",
                    'country' => "MX",
                    'postal_code' => "06100"
                  ]
                ]
              ]
            ]
          );

        return $customer;
        // return view('payment.conekta');
      }
}
