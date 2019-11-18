<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function subCategoriesCount($id){
        return SubCategory::where('category_id',$id)->count();
    }
}
