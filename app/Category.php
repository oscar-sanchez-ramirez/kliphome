<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function subCategoriesCount($id){
        return SubCategory::where('category_id',$id)->count();
    }
    public function SubCategories($title){
        return DB::table('categories as c')->join('sub_categories as s','c.id','s.category_id')->select('c.*')->where('c.title',$title)->get();
    }
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
