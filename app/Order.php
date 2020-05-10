<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function clientName($id){
        return User::where('id',$id)->first(['name','lastName','email','phone','avatar']);
    }
    public function clientAddress($id){
        return Address::where('id',$id)->first(['alias','street']);
    }
    public function orderCoupon($coupon_code){
        // Log::notice($coupon);
        $coupon = Coupon::where('code',$coupon_code)->first();
        if(!empty($coupon)){
            return $coupon;
        }else{
            $admin_coupon = AdminCoupon::where('code',$coupon_code)->where('is_charged','N')->first();
            return $admin_coupon;
        }
    }
    public function getService($type,$id){
        switch ($type) {
            case 'SubService':
                return SubService::where('id',$id)->first('title');
                break;
            case 'Service':
                return Service::where('id',$id)->first('title');
                break;
            case 'Category':
                return Category::where('id',$id)->first('title');
                break;
            default:
                # code...
                break;
        }
    }
}
