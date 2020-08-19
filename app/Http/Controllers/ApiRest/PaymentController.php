<?php

namespace App\Http\Controllers\ApiRest;

use Stripe;
use App\User;
use App\UserCard;
use App\TempPayment;
use App\ConfigSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class PaymentController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api',['only'=>['revisar_pago_temp','saveCustomer']]);
        \Conekta\Conekta::setApiKey(ConfigSystem::conekta_key);
        \Conekta\Conekta::setLocale('es');
    }
    public function saveCustomer(Request $request)
    {
        $user = $request->user();
        Stripe\Stripe::setApiKey("sk_live_cgLVMsCuyCsluw3Tznx1RuPS00UJQp8Rqf");
        try {
            $random = str_random(5);
            $customer = Stripe\Customer::create ([
                "source" => $request->stripeToken,
                "description" => "Card of".$user->name.' '.$user->lastName.'-'.$random
            ]);
            return response()->json([
                'success' => true,
                'card' => $customer
            ]);
          } catch(\Stripe\Exception\CardException $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                "type" => "1"
            ]);
          } catch (\Stripe\Exception\RateLimitException $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                "type" => "2"
            ]);
          } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error($e);
            // Invalid parameters were supplied to Stripe's API
            return response()->json([
                'success' => false,
                "type" => "3"
            ]);
          } catch (\Stripe\Exception\AuthenticationException $e) {
            Log::error($e);
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            return response()->json([
                'success' => false,
                "type" => "4"
            ]);
          } catch (\Stripe\Exception\ApiConnectionException $e) {
            Log::error($e);
            // Network communication with Stripe failed
            return response()->json([
                'success' => false,
                "type" => "5"
            ]);
          } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error($e);
            // Display a very generic error to the user, and maybe send
            // yourself an email
            return response()->json([
                'success' => false,
                "type" => "6"
            ]);
          } catch (Exception $e) {
            Log::error($e);
            // Something else happened, completely unrelated to Stripe
            return response()->json([
                'success' => false,
                "type" => "7"
            ]);
          }


    }
    public function conekta(){
      $order = \Conekta\Order::create([
        'currency' => 'MXN',
        'customer_info' => [
          'customer_id' => 'tok_2oCf7hpXpq3vbUFzL'
        ],
        'line_items' => [
          [
            'name' => 'Box of Cohiba S1s',
            'unit_price' => 35,
            'quantity' => 1
          ]
        ],
        'charges' => [
          [
            'payment_method' => [
              'type' => 'default'
            ]
          ]
        ]
      ]);
      return $order;
      // $valid_order =
      // [
      //     'line_items'=> [
      //         [
      //             'name'        => 'Box of Cohiba S1s',
      //             'description' => 'Imported From Mex.',
      //             'unit_price'  => 20000,
      //             'quantity'    => 1,
      //             'sku'         => 'cohb_s1',
      //             'category'    => 'food',
      //             'tags'        => ['food', 'mexican food']
      //         ]
      //     ],
      //     'currency' => 'mxn',
      //     'metadata' => ['test' => 'extra info'],
      //     'charges'  => [
      //         [
      //             'payment_method' => [
      //                 'type'       => 'oxxo_cash',
      //                 'expires_at' => strtotime(date("Y-m-d H:i:s")) + "36000"
      //             ],
      //             'amount' => 20000,
      //         ]
      //     ],
      //     'currency' => 'mxn',
      //     'customer_info' => [
      //         'name'  => 'John Constantine',
      //         'phone' => '+5213353319758',
      //         'email' => 'hola@hola.com',
      //     ]
      // ];
      // try {
      //   $order = \Conekta\Order::create($valid_order);
      // } catch (\Conekta\ProcessingError $e){
      //   echo $e->getMessage();
      // } catch (\Conekta\ParameterValidationError $e){
      //   echo $e->getMessage();
      // } finally (\Conekta\Handler $e){
      //   echo $e->getMessage();
      // }
      // return view('payment.conekta');
    }
    public function conekta_pago(Request $request){
        $user_id = $request->user_id;
        $monto = $request->monto;
        $type = $request->type;
        return view('payment.conekta',compact('user_id','monto','type'));
    }
    public function conekta_nuevo_pago(Request $request){
        Log::notice($request->all());
        $user = User::where('id',$request->user_id)->first();
        $price = floatval($request->monto);
        if(substr($request->token,0,3) == "tok"){
            $pago = \Conekta\Order::create(
                [
                    "line_items" => [["name" => "PAGO POR VISITA","unit_price" => $price * 100,"quantity" => 1]],
                    "currency" => "MXN",
                    "customer_info" => ["name" => $user->name.' '.$user->lastName,"email" => $user->email,"phone" => $user->phone],
                    "charges" => [["payment_method" => ["type" => "card","token_id" => $request->token]]
                    ]
                ]
                );
        }else if(substr($request->token,0,3) == "cus"){
            $pago = \Conekta\Order::create([
                'currency' => 'MXN',
                'customer_info' => ['customer_id' => $request->token],
                "line_items" => [["name" => "PAGO POR VISITA","unit_price" => $price * 100,"quantity" => 1]],
                'charges' => [['payment_method' => ['type' => 'default']]]
            ]);
        }
        if($pago->payment_status == "paid"){
            $payment = new TempPayment;
            $payment->user_id = $request->user_id;
            $payment->code_payment = $pago->id;
            // $payment->code_payment = "abc";
            $payment->description = $request->type;
            $payment->state = true;
            $payment->price = $price;
            $payment->save();
            if($request->guardar_tarjeta == 'true'){
                $this->guardar_tarjeta($request);
            }
            // dispatch(new NotifyNewOrder($order->id,$user->email));
            return response()->json([
                'success' => true,
                'message' => "Pago exitoso",
            ]);
        }else{
            return response()->json([
                'success' => false
            ]);
        }

    }
    public function revisar_pago_temp(Request $request){
        $user = $request->user();
        $temp = TempPayment::where('user_id',$user->id)->first();
        if($temp){
            return response()->json([
                'success' => true,
                'message' => "Pago encontrado",
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Pago no encontrado",
            ]);
        }
    }

    private function guardar_tarjeta($request){
        try {
            Log::notice("entrando a guardar tarjeta");
            $user = User::where('id',$request->user_id)->first();
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
            $this->guardar_usuario($customer["payment_sources"][0],$user->id);


        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
    private function guardar_usuario($customer,$user_id){
        Log::notice("entrando a guardar usuario");
        $cus = new UserCard;
        $cus->user_id = $user_id;
        $cus->brand = $customer->brand;
        $cus->exp_month = $customer->exp_month;
        $cus->exp_year = $customer->exp_year;
        $cus->last4 = $customer->last4;
        $cus->name = $customer->name;
        $cus->idToken = $customer->parent_id;
        $cus->save();
    }

}
