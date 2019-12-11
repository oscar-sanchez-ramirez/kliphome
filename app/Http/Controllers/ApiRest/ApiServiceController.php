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
    public function getSubCategories($category){
        $subCategories = DB::table('categories as c')->join('sub_categories as s','c.id','s.category_id')->where('c.title',$category)->select('s.title')->get();
        return Response(json_encode(array('subCategories' => $subCategories)));
    }
    public function getServices($subCategory){
        $services =  new ServiceCollection(SubCategory::where('title',$subCategory)->first());
        return Response(json_encode(array('services' => $services)));
    }
    public function getCategories(){
        $categories = Category::all('id','title');
        return Response(json_encode(array('categories' => $categories)));
    }
    public function userInfo($id){
        $user = User::where('id',$id)->first();
        if($user->type == "AppFixerMan"){
            $delegation = DB::table('selected_delegations as s')->join('delegations as d','s.delegation_id','d.id')->select('s.id','d.id as delegation_id','d.title')->where('s.user_id',$user->id)->get();
            $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$user->id)->get();
            Log::notice($categories);
            $ids = array_column($categories->toArray(), 'category_id');
            Log::notice($ids);
            $orders = $this->categories($ids,$delegation[0]->delegation_id);
            return response()->json([
                'user' => $user,
                'delegations' => $delegation,
                'categories' => $categories,
                'orders' => $orders
            ]);
        }elseif($user->type == "AppUser"){
            $address = Address::where('user_id',$user->id)->get();
            return array($user,$address);
        }

    }
    public function categories($ids,$delegation_id)
    {
        $final_orders = [];
        $orders = DB::table('orders as o')->join('addresses as a','o.address','a.id')->where('a.delegation',$delegation_id)
                ->where('o.state','FIXERMAN_NOTIFIED')->orWhere('state','PENDING')->select('o.*'.'a.delegation','a.address')->get();
        foreach ($orders as $key) {
            $category = $this->table($key->type_service,$key->selected_id);
            Log::notice($category);
            $result = in_array($category[0]->id,$ids);
            if($result){
                array_push($final_orders,$key);
            }
        }
        return $final_orders;
    }

    private function table($type_service,$id){

        switch ($type_service) {
            case 'SubService':
                $category = DB::table('sub_services as subse')->join('services as se','se.id','subse.service_id')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title','ca.id')->where('subse.id',$id)->get();
                return $category;
                break;

            default:
                # code...
                break;
        }

    }
}
