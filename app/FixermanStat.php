<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FixermanStat extends Model
{
    //
    public function parent(){
        return $this->belongsTo(User::class, 'id');
    }
}
