<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $csts = ['written_by_me' => 'boolean'];
}
