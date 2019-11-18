<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    public function CategoryName($id){
        return Category::where('id',$id)->first('title');
    }
}
