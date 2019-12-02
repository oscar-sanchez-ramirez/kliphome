<?php

namespace App;
use App\Category;
use Illuminate\Database\Eloquent\Model;

class SelectedCategories extends Model
{
    public function parent(){
        return $this->belongsTo(Category::class, 'id');
    }
}
