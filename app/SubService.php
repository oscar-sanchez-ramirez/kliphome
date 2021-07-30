<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubService extends Model
{
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function SubServiceName($id){
        return Service::where('id',$id)->first('title');
    }
}
