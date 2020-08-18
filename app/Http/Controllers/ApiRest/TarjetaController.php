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

          Log::notice($customer["payment_sources"][0]);

        //   $customer["usuario"]["payment_sources"]["data"][0]["user_id"] = $user->id;
        //   $this->saveCustomer($customer["usuario"]["payment_sources"]["data"][0]);
        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
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
        //
    }

    private function saveCustomer($customer){
        $cus = new UserCard;
        $cus->user_id = $customer->user_id;
        $cus->brand = $customer->brand;
        $cus->exp_month = $customer->exp_month;
        $cus->exp_year = $customer->exp_year;
        $cus->last4 = $customer->last4;
        $cus->name = $customer->name;
        $cus->parent_id = $customer->parent_id;
        $cus->save();
    }
}
