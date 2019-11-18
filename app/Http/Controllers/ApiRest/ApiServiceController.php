<?php

namespace App\Http\Controllers\ApiRest;

// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCollection;
use App\SubCategory;
use DB;

class ApiServiceController extends Controller
{
    public function getSubCategories($category){
        $subCategories = DB::table('categories as c')->join('sub_categories as s','c.id','s.category_id')->where('c.title',$category)->select('s.title')->get();
        return Response(json_encode(array('subCategories' => $subCategories)));
    }
    public function getServices($subCategory){
        $services =  new ServiceCollection(SubCategory::where('title',$subCategory)->first());
        return Response(json_encode(array('services' => $services)));
    }
}
