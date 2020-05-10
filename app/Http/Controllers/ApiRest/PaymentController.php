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
        $user = $request->user();
        Stripe\Stripe::setApiKey("sk_test_f2VYH7q0KzFbrTeZfSvSsE8R00VBDQGTPN");
        try {
            $customer = Stripe\Customer::create ([
                "source" => $request->stripeToken,
                "description" => "Card of".$user->name.' '.$user->lastName
            ]);
            return response()->json([
                'success' => true,
                'card' => $customer
            ]);
          } catch(\Stripe\Exception\CardException $e) {
            return response()->json([
                'success' => false,
                "type" => "1"
            ]);
          } catch (\Stripe\Exception\RateLimitException $e) {
            return response()->json([
                'success' => false,
                "type" => "2"
            ]);
          } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            return response()->json([
                'success' => false,
                "type" => "3"
            ]);
          } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            return response()->json([
                'success' => false,
                "type" => "4"
            ]);
          } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            return response()->json([
                'success' => false,
                "type" => "5"
            ]);
          } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            return response()->json([
                'success' => false,
                "type" => "6"
            ]);
          } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            return response()->json([
                'success' => false,
                "type" => "7"
            ]);
          }


    }
}
