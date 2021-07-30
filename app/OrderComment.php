<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderComment extends Model
{
    protected $fillable = ['comment','order_id'];
}
