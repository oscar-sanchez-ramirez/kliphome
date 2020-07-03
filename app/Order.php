<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function clientName($id){
        return User::where('id',$id)->first(['name','lastName','email','phone','avatar']);
    }
    public function clientAddress($id){
        return Address::where('id',$id)->first(['alias','street','exterior','interior','municipio','postal_code','colonia','reference']);
    }
    public function quotation($id){
        $quotation = Quotation::where('order_id',$id)->orderBy('id','DESC')->first();
        if($quotation){
            if($quotation->state == 0){
                return "Cot. pendiente";
            }else if($quotation->state == 1){
                return "Cot. pagada";
            }else if($quotation->state == 2){
                return "Cot. cancelada";
            }
        }else{

            return "Sin Cotización";
        }
    }
    public function orderCoupon($coupon_code){
        $coupon = User::where('code',$coupon_code)->first();
        if(!empty($coupon)){
            $coupon->discount = 5;
            return $coupon;
        }else{
            $admin_coupon = AdminCoupon::where('code',$coupon_code)->first();
            return $admin_coupon;
        }
    }
    public function getService($type,$id){
        switch ($type) {
            case 'Category':
                $category = DB::table('categories')->select('title as category')->where('id',$id)->get();
                return $category[0]->category;
                break;
            case 'SubCategory':
                $category  = DB::table('sub_categories as su')->join('categories as ca','su.category_id','ca.id')->select('ca.title as category','su.title as sub_category')->where('su.id',$id)->get();
                return $category[0]->category.'/'.$category[0]->sub_category;
                break;
            case 'Service':
                $category = DB::table('services as se')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('se.title as service','ca.title as category','su.title as sub_category')->where('se.id',$id)->get();
                return $category[0]->category.'/'.$category[0]->sub_category.'/'.$category[0]->service;
                break;
            case 'SubService':
                $category = DB::table('sub_services as subse')->join('services as se','se.id','subse.service_id')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title as category','su.title as sub_category','subse.title as service','se.title as serviceTrait')->where('subse.id',$id)->get();
                return $category[0]->category.'/'.$category[0]->sub_category.'/'.$category[0]->serviceTrait.'/'.$category[0]->service;
                break;
            default:
                # code...
                break;
        }
    }
}
