<?php

namespace App\Http\Controllers\ApiRest;

use DB;
use App\User;
use App\Cita;
use App\Order;
use App\Address;
use App\Coupon;
use App\Quotation;
use App\AdminCoupon;
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
    public function historyOrders(Request $request,$page){
        $page = (5 * $page);
        $user = $request->user();
        $orders = DB::table('orders as o')
        ->join('addresses as a','o.address','a.id')
        ->select('o.*','a.alias','a.street as address','a.municipio','a.colonia','a.postal_code','a.exterior','a.interior','a.reference')
        ->where('o.user_id',$user->id)->offset($page)->take(5)->orderBy('o.id',"DESC")->get();

        $fetch_categories = new ApiServiceController();
        foreach ($orders as $key) {
            if($key->state == "FIXERMAN_APPROVED" || $key->state == "FIXERMAN_DONE" || $key->state == "QUALIFIED" ){
                $user = DB::table('selected_orders as so')->join('users as u','u.id','so.user_id')
                ->where('so.state',1)->where('order_id',$key->id)->select('u.*','so.created_at as orderAcepted','so.id as idOrderAccepted')->get();
                if(count($user)>0){
                    $userArray = json_decode( json_encode($user), true);
                    $key->name = $userArray[0]["name"];
                    $key->lastName = $userArray[0]["lastName"];
                    $key->fixerman_id = $userArray[0]["id"];
                    $key->avatar = $userArray[0]["avatar"];
                    $key->orderAcepted = $userArray[0]["orderAcepted"];
                    $key->idOrderAccepted = $userArray[0]["idOrderAccepted"];
                }
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
    public function orderDetail(Request $request,$order_id){
        $user = $request->user();
        $orders = DB::table('orders as o')
        ->join('addresses as a','o.address','a.id')
        ->leftJoin('selected_orders as so','o.id','so.order_id')
        ->leftJoin('users as u','u.id','so.user_id')
        ->select('o.*','a.alias','a.street as address','u.name','u.lastName','u.id as fixerman_id','u.avatar','so.created_at as orderAcepted','so.id as idOrderAccepted')
        ->where('o.id',$order_id)->get();
        $check_coupon = null;
        $pre_coupon= null;
        $type_coupon = 'pre_coupon';
        if($orders[0]->pre_coupon != ""){
            $pre_coupon = AdminCoupon::where('code',$orders[0]->pre_coupon)->first();
            if(empty($pre_coupon)){
                // $type_coupon = 'pre_coupon';
                $pre_coupon = Coupon::where('code',$user->code)->where('is_charged','N')->first();
                $pre_coupon["discount"] = 5;
            }
        }
        // else{
        //     $check_coupon = Coupon::where('code',$user->code)->where('is_charged',"N")->first();
        // }
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
        return Response(json_encode(array('orders' => $orders,"coupon"=>$check_coupon,"pre_coupon"=>$pre_coupon,"type_coupon"=>$type_coupon)));
    }
    public function quotationDetail($id){
        $quotation = Quotation::where('id',$id)->first();
        $latest_quotations = Quotation::where('order_id',$quotation->order_id)->where('id','!=',$quotation->id)->get();
        return response()->json([
            'quotation' => $quotation,
            'latest_quotations' => $latest_quotations
        ]);
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
    public function confirmArriveDate(Request $request){
        $fixerman = User::where('id',$request->user_id)->first();
        $client = User::where('id',$request->to_id)->first();
        Cita::where('id',$request->date_id)->update([
            'is_notified' => 1
        ]);
        // $client->notify(new ConfirmArrive($request->order_id,$fixerman->name,$client->email));
    }
    public function list_payments(Request $request){
        $user = $request->user();
        $payments = DB::table('payments as p')->join('orders as o','p.order_id','o.id')
        ->select('p.*')->where('o.user_id',$user->id)->where('p.state',1)->get();
        return response()->json([
            'payments' => $payments
        ]);
    }
}
