<?php

namespace App;
use App\Category;
use Illuminate\Database\Eloquent\Model;

class SelectedCategories extends Model
{
    protected $table = 'selected_categories';
    protected $primaryKey = 'id';
    public function parent(){
        return $this->belongsTo(Category::class, 'id');
    }
}
