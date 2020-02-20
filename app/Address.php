<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'alias', 'address','user_id','delegation','postal_code','reference','latitud','longitud'
    ];

}
