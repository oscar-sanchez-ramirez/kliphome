<?php

namespace App\Http\Controllers\ApiRest;

use App\User;
use App\ConfigSystem;
use Illuminate\Http\Request;
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
}
