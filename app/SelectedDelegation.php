<?php

namespace App;
use App\Delegation;
use Illuminate\Database\Eloquent\Model;

class SelectedDelegation extends Model
{
    protected $table = 'selected_delegations';
    protected $primaryKey = 'id';
    public function parent(){
        return $this->belongsTo(Delegation::class, 'id');
    }
}
