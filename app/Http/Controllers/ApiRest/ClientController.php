<?php

namespace App\Http\Controllers\ApiRest;

use DB;
use App\User;
use App\Order;
use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApiController;
use App\Notifications\Database\ConfirmArrive;
use App\Http\Controllers\ApiRest\ApiServiceController;

class ClientController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    public function historyOrders($id){
        $orders = DB::table('orders as o')
        ->join('addresses as a','o.address','a.id')
        ->select('o.*','a.alias','a.street as address')->where('o.user_id',$id)->orderBy('o.id',"DESC")->get();
        Log::notice($orders);
        $fetch_categories = new ApiServiceController();
        foreach ($orders as $key) {
            if($key->state == "FIXERMAN_APPROVED" || $key->state == "FIXERMAN_DONE" || $key->state == "QUALIFIED"){
                $user = DB::table('selected_orders as so')->join('users as u','u.id','so.user_id')
                ->where('so.state',1)->where('order_id',$key->id)->select('u.*','so.created_at as orderAcepted','so.id as idOrderAccepted')->get();
                Log::notice($user);
                $userArray = json_decode( json_encode($user), true);
                $key->name = $userArray[0]["name"];
                $key->lastName = $userArray[0]["lastName"];
                $key->fixerman_id = $userArray[0]["id"];
                $key->avatar = $userArray[0]["avatar"];
                $key->orderAcepted = $userArray[0]["orderAcepted"];
                $key->idOrderAccepted = $userArray[0]["idOrderAccepted"];
            }
            $category = $fetch_categories->table($key->type_service, $key->selected_id);
            $key->category = $category[0]->category;
            if ($key->type_service == "Category") {
                $key->sub_category = "-";
            }else{
                $key->sub_category = $category[0]->sub_category;
            }
            $key->serviceTrait = $category[0]->service;
            $key->visit_price = $category[0]->visit_price;
        }
        return Response(json_encode(array('orders' => $orders)));
    }
    public function orderDetail($order_id){
        $orders = DB::table('orders as o')
        ->join('addresses as a','o.address','a.id')
        ->leftJoin('selected_orders as so','o.id','so.order_id')
        ->leftJoin('users as u','u.id','so.user_id')
        ->select('o.*','a.alias','a.street as address','u.name','u.lastName','u.id as fixerman_id','u.avatar','so.created_at as orderAcepted','so.id as idOrderAccepted')
        ->where('o.id',$order_id)->get();
        $fetch_categories = new ApiServiceController();
        foreach ($orders as $key) {
            $category = $fetch_categories->table($key->type_service, $key->selected_id);
            $key->category = $category[0]->category;
            if ($key->type_service == "Category") {
                $key->sub_category = "-";
            }else{
                $key->sub_category = $category[0]->sub_category;
            }
            $key->serviceTrait = $category[0]->service;
        }
        return Response(json_encode(array('orders' => $orders)));
    }
    public function addAddress(Request $request){
        $add = new Address;
        $add->alias = $request->alias;
        $add->address = $request->address;
        $add->user_id = $request->user_id;
        $add->delegation = "-";
        $add->save();

        $address = Address::where('user_id',$request->user_id)->get();
        return response()->json([
            'address' => $address
        ]);
    }
    public function deleteAddress(Request $request){
        Address::where('user_id',$request->user_id)->where('alias',$request->alias)->delete();
        $address = Address::where('user_id',$request->user_id)->get();
        return response()->json([
            'address' => $address
        ]);
    }
    public function confirmArrive(Request $request){
        $fixerman = User::where('id',$request->user_id)->first();
        $client = User::where('id',$request->to_id)->first();
        Order::where('id',$request->order_id)->update([
            'fixerman_arrive' => "SI"
        ]);
        $client->notify(new ConfirmArrive($request->order_id,$fixerman->name,$client->email));

    }
}
