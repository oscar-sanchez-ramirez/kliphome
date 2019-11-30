<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    protected $hidden = [
        'created_at', 'updated_at','state',
    ];
}
