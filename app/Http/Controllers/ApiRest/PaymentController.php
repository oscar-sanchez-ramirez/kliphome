<?php

namespace App\Http\Controllers\ApiRest;

use Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class PaymentController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    public function saveCustomer(Request $request)
    {
        Log::notice($request->all());
        $user = $request->user();
        Stripe\Stripe::setApiKey("sk_test_f2VYH7q0KzFbrTeZfSvSsE8R00VBDQGTPN");
        $customer = Stripe\Customer::create ([
            "source" => $request->stripeToken,
            "description" => "Card of".$user->name.' '.$user->lastName
        ]);
        Log::notice($customer);
        return response()->json([
            'success' => true,
            'card' => $customer
        ]);
    }
}
