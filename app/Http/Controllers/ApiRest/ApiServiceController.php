<?php

namespace App\Http\Controllers\ApiRest;

// use Illuminate\Http\Request;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ServiceCollection;
use Illuminate\Support\Facades\Log;
use App\SubCategory;
use DB;
use App\Address;
use App\User;

class ApiServiceController extends ApiController
{
    //Getting sub-categories for clientApp
    public function getSubCategories($category){
        $subCategories = DB::table('categories as c')->join('sub_categories as s','c.id','s.category_id')->where('c.title',$category)->select('s.title')->get();
        return Response(json_encode(array('subCategories' => $subCategories)));
    }
    //Getting services for clientApp
    public function getServices($subCategory){
        $services =  new ServiceCollection(SubCategory::where('title',$subCategory)->first());
        return Response(json_encode(array('services' => $services)));
    }
    //Getting categories for clientApp
    public function getCategories(){
        $categories = Category::all('id','title');
        return Response(json_encode(array('categories' => $categories)));
    }
    //Return info after login, conditional if user is client or fixerman
    public function userInfo($id){
        $user = User::where('id',$id)->first();
        if($user->type == "AppFixerMan"){
            $delegation = DB::table('selected_delegations as s')->join('delegations as d','s.delegation_id','d.id')->select('s.id','d.id as delegation_id','d.title')->where('s.user_id',$user->id)->get();
            $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$user->id)->get();
            $ids = array_column($categories->toArray(), 'category_id');
            $selectedOrders = DB::table('selected_orders')->where('user_id',$user->id)->where('state',true)->pluck('order_id');
            $orders = $this->categories($ids,$delegation[0]->delegation_id,$selectedOrders);
            $accepted = $this->ordersAccepted($selectedOrders);
            return response()->json([
                'user' => $user,
                'delegations' => $delegation,
                'categories' => $categories,
                'orders' => $orders,
                'accepted' => $accepted
            ]);
        }elseif($user->type == "AppUser"){
            $address = Address::where('user_id',$user->id)->get();
            return array($user,$address);
        }

    }
    //getting categories by fixerman preferences
    public function categories($ids,$delegation_id,$selectedOrders)
    {
        $final_orders = [];
        $orders = DB::table('orders as o')->join('users as u','u.id','o.user_id')->join('addresses as a','o.address','a.id')->where('a.delegation',$delegation_id)->whereNotIn('o.id',$selectedOrders)
        ->where(function($query){ return $query->where('o.state','FIXERMAN_NOTIFIED')->orWhere('o.state','PENDING');})->select('o.*','a.delegation','a.address','u.name','u.lastName')->get();
        foreach ($orders as $key) {
            $category = $this->table($key->type_service,$key->selected_id);
            $result = in_array($category[0]->id,$ids);
            $key->service = $category[0]->service;
            if($result){
                array_push($final_orders,$key);
            }
        }
        return $final_orders;
    }
    public function ordersAccepted($selectedOrders){
        $final_orders = [];
        $orders = DB::table('orders as o')->join('users as u','u.id','o.user_id')->join('addresses as a','o.address','a.id')->whereIn('o.id',$selectedOrders)
        ->where(function($query){ return $query->where('o.state','FIXERMAN_NOTIFIED')->orWhere('o.state','PENDING');})->select('o.*','a.delegation','a.address','u.name','u.lastName')->get();
        foreach ($orders as $key) {
            $category = $this->table($key->type_service,$key->selected_id);
            $key->service = $category[0]->service;
            array_push($final_orders,$key);
        }
        return $final_orders;
    }
    //Query will depend of order selected
    private function table($type_service,$id){

        switch ($type_service) {
            case 'SubService':
                $category = DB::table('sub_services as subse')->join('services as se','se.id','subse.service_id')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title','ca.id','subse.title as service')->where('subse.id',$id)->get();
                return $category;
                break;

            default:
                # code...
                break;
        }

    }
}
