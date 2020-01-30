<?php

namespace App\Http\Controllers\ApiRest;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiRest\ApiServiceController;
use DB;
use App\Address;

class ClientController extends ApiController
{
    public function historyOrders($id){
        $orders = DB::table('orders as o')
        ->join('addresses as a','o.address','a.id')
        ->leftJoin('selected_orders as so','o.id','so.order_id')
        ->leftJoin('users as u','u.id','so.user_id')
        ->select('o.*','a.alias','a.address','u.name','u.lastName','u.id as fixerman_id','u.avatar','so.created_at as orderAcepted','so.id as idOrderAccepted')->where('o.user_id',$id)->orderBy('o.id',"DESC")->get();
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
            $key->visit_price = $category[0]->visit_price;
        }
        return Response(json_encode(array('orders' => $orders)));
    }
    public function orderDetail($id,$order_id){
        $orders = DB::table('orders as o')
        ->join('addresses as a','o.address','a.id')
        ->leftJoin('selected_orders as so','o.id','so.order_id')
        ->leftJoin('users as u','u.id','so.user_id')
        ->select('o.*','a.alias','a.address','u.name','u.lastName','u.id as fixerman_id','u.avatar','so.created_at as orderAcepted','so.id as idOrderAccepted')
        ->where('o.user_id',$id)->where('o.id',$order_id)->get();
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
            $key->visit_price = $category[0]->visit_price;
        }
        return Response(json_encode(array('orders' => $orders)));
    }
    public function addAddress(Request $request){
        $address = $request->address;

        if((strpos($address, "Ciudad de México") !== false) || strpos($address, "CDMX") || strpos($address, "Méx., México")){
            $delegation = "1";
        } elseif(strpos($address, "Guadalajara") !== false){
            $delegation = "2";
        }
        $add = new Address;
        $add->alias = $request->alias;
        $add->address = $request->address;
        $add->user_id = $request->user_id;
        $add->delegation = $delegation;
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
}
