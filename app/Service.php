<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $appends = ['subcategory_title'];
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
