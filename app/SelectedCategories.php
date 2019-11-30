<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SelectedCategories extends Model
{
    public function parent(){
        return $this->belongsTo(Category::class, 'id');
    }
}
