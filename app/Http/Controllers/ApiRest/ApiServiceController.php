<?php

namespace App\Http\Controllers\ApiRest;

// use Illuminate\Http\Request;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ServiceCollection;
use App\SubCategory;
use DB;

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
        $categories = Category::all('title');
        return Response(json_encode(array('categories' => $categories)));
    }
}
