<?php

namespace App;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function subCategoriesCount($id){
        return SubCategory::where('category_id',$id)->count();
    }
    public function SubCategories($title){
        return DB::table('categories as c')
        ->join('sub_categories as s','c.id','s.category_id')->LeftJoin('services as se','se.subcategory_id','s.id')
        ->select('s.title','s.id',DB::raw('count(se.id) as services_count'))->groupBy('s.id','s.title')
        ->where('c.title',$title)
        ->get();
    }
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
