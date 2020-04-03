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
    public function __construct(){
        $this->middleware('auth:api', ['only' => ['getSubCategories']]);
    }
    //Getting sub-categories for clientApp
    public function getSubCategories($category_p){
        $category = new Category;
        $subCategories = $category->SubCategories($category_p);
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
            $delegation = DB::table('selected_delegations as s')->where('s.user_id',$user->id)->get()->toArray();
            $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$user->id)->get()->toArray();
            $categorias = array_column($categories, 'category_id');
            $municipio = array_column($delegation,'municipio');
            $orders = $this->categories($categorias,$municipio,$user->id);
            $notifications  = DB::table('notifications')->where('notifiable_id',$user->id)->where('read_at',null)->count();
            $accepted = $this->ordersAccepted($user->id);
            return response()->json([
                'user' => $user,
                'delegations' => $delegation,
                'categories' => $categories,
                'orders' => $orders,
                'accepted' => $accepted,
                'notifications' => $notifications
            ]);
        }elseif($user->type == "AppUser"){
            $categories = Category::all();
            $address = Address::where('user_id',$user->id)->get();
            $notifications  = DB::table('notifications')->where('notifiable_id',$user->id)->where('read_at',null)->count();
            return response()->json([
                'user' => $user,
                'address' => $address,
                'categories' => $categories,
                'notifications' => $notifications
            ]);
        }
    }
    //getting categories by fixerman preferences
    public function categories($ids,$municipio,$user_id)
    {
        $selectedOrders = DB::table('selected_orders')->where('user_id',$user_id)->pluck('order_id');
        $final_orders = [];
        $orders = DB::table('orders as o')->join('users as u','u.id','o.user_id')->join('addresses as a','o.address','a.id')
        ->where(function ($query){
                $query->where('o.state','FIXERMAN_NOTIFIED')->orWhere('o.state','PENDING');
        })->whereIn('a.municipio',$municipio)->whereNotIn('o.id',$selectedOrders)
        ->select('o.*','a.delegation','a.street as address','a.postal_code','a.municipio','u.name','u.lastName','u.avatar')->orderBy('o.created_at',"DESC")->get();
        foreach ($orders as $key) {
            $category = $this->table($key->type_service,$key->selected_id);
            $result = in_array($category[0]->id,$ids);
            $key->service = $category[0]->service;
            $key->category = $category[0]->category;
            if ($key->type_service == "Category") {
                $key->sub_category = "-";
            }else{
                $key->sub_category = $category[0]->sub_category;
            }
            $key->serviceTrait = $category[0]->service;
            if($result){
                array_push($final_orders,$key);
            }
        }
        return $final_orders;
    }
    public function ordersAccepted($user_id){
        $final_orders = [];

        $orders = DB::table('orders as o')
        ->join('users as u','u.id','o.user_id')
        ->join('addresses as a','o.address','a.id')
        ->join('selected_orders as so','o.id','so.order_id')
        ->where('so.user_id',$user_id)->where('so.state',1)
        ->select('o.*','a.municipio','a.alias','a.street as address','u.name','u.lastName','u.avatar','so.id as idOrderAccepted','so.created_at as orderAcepted')->distinct('o.id')->orderBy('o.created_at',"DESC")->get();
        foreach ($orders as $key) {
            $category = $this->table($key->type_service,$key->selected_id);
            $key->service = $category[0]->service;
            // $key->service = $category[0]->service;
            $key->category = $category[0]->category;
            if ($key->type_service == "Category") {
                $key->sub_category = "-";
            }else{
                $key->sub_category = $category[0]->sub_category;
            }
            $key->serviceTrait = $category[0]->service;
            array_push($final_orders,$key);
        }
        return $final_orders;
    }
    //Query will depend of order selected
    public function table($type_service,$id){

        switch ($type_service) {
            case 'Category':
                $category = DB::table('categories')->select('title as service','id','title as category','visit_price')->where('id',$id)->get();
                return $category;
                break;
            case 'SubCategory':
                $category  = DB::table('sub_categories as su')->join('categories as ca','su.category_id','ca.id')->select('ca.title','ca.id','ca.visit_price','su.title as service','ca.title as category','su.title as sub_category','su.title as serviceTrait')->where('su.id',$id)->get();
                return $category;
                break;
            case 'Service':
                $category = DB::table('services as se')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title','ca.id','ca.visit_price','se.title as service','ca.title as category','su.title as sub_category','se.title as serviceTrait')->where('se.id',$id)->get();
                return $category;
                break;
            case 'SubService':
                $category = DB::table('sub_services as subse')->join('services as se','se.id','subse.service_id')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title','ca.id','ca.visit_price','subse.title as service','ca.title as category','su.title as sub_category','se.title as serviceTrait')->where('subse.id',$id)->get();
                return $category;
                break;

            default:
                # code...
                break;
        }

    }

}
