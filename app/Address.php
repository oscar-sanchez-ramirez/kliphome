<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'alias', 'street','user_id','delegation','postal_code','reference','exterior','interior','colonia'
    ];

}
