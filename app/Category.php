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
        Log::notice($title);
        return DB::table('categories as c')
        ->join('sub_categories as s','c.id','s.category_id')->join('services as se','se.subcategory_id','s.id')
        ->select('s.title',DB::raw('count(se.id) as user_count'))
        ->where('c.title',$title)
        ->get();
    }
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
