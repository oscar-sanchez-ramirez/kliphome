<?php

namespace App\Http\Controllers\ApiRest;

use App\User;
use App\UserCard;
use App\ConfigSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApiController;

class TarjetaController extends ApiController
{
    public function __construct(){
        \Conekta\Conekta::setApiKey(ConfigSystem::conekta_key);
        \Conekta\Conekta::setLocale('es');
        $this->middleware('auth:api',['only'=>['listar_cards_conekta']]);
    }

    protected $user_id;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        return view('payment.nueva_tarjeta',compact('user_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
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
            $this->saveCustomer($customer["payment_sources"][0],$user->id);
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'success' => false
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = \Conekta\Customer::find($id);
        Log::notice($customer);
        $customer->delete();
        UserCard::where('idToken',$id)->delete();

    }

    private function saveCustomer($customer,$user_id){
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

    public function listar_cards_conekta(Request $request){
        $user = $request->user();
        $cards = UserCard::where('user_id',$user->id)->get();
        return response()->json([
            'success' => true,
            'cards' => $cards
        ]);
    }
}
