<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $appends = ['subcategory_title'];

    public function allServices(){
        return DB::table('services as se')->join('sub_categories as subca','subca.id','se.subcategory_id')->join('categories as c','c.id','subca.category_id')->select('se.*','subca.title as subcategory','c.title as category')->get();
    }
    public function servicesByCategory($category_id){
        return DB::table('services as se')->join('sub_categories as subca','subca.id','se.subcategory_id')->join('categories as c','c.id','subca.category_id')->select('se.*','subca.title as subcategory','c.title as category')->where('c.id',$category_id)->get();
    }
    public function getSubCategoryTitleAttribute()
    {
        return $this->subcategory()->first(['title']);
    }
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function subServicesCount($id){
        return SubService::where('service_id',$id)->count();
    }
    public function children(){
        return $this->hasMany(SubService::class, 'service_id')->orderBy('title', 'asc');
    }

}
