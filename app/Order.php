<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function clientName($id){
        return User::where('id',$id)->first(['name','lastName','email','phone']);
    }
    public function clientAddress($id){
        return Address::where('id',$id)->first(['alias','address']);
    }
    public function getService($type,$id){
        switch ($type) {
            case 'SubService':
                return SubService::where('id',$id)->first('title');
                break;
            case 'Service':
                return Service::where('id',$id)->first('title');
                break;
            default:
                # code...
                break;
        }
    }
}
