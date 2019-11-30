<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SelectedDelegation extends Model
{
    public function parent(){
        return $this->belongsTo(Delegation::class, 'id');
    }
}
