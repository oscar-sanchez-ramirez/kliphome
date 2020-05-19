<?php

namespace App\Http\Controllers\Admin;

use App\AdminCoupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class CuponController extends Controller
{
    public function index(){
        $coupons = AdminCoupon::orderBy('id','DESC')->paginate(10);
        return view('admin.cupones.index')->with('coupons',$coupons);
    }
    public function nuevo(){
        return view('admin.cupones.new_coupon');
    }
    public function save(Request $request){
        $check_coupon = AdminCoupon::where('code',$request->code)->first();
        if($check_coupon){
            return back()->with('failed',"El cupón ya existe");
        }else{
            $new_coupon = new AdminCoupon;
            $new_coupon->code = $request->code;
            $new_coupon->discount = $request->discount;
            $new_coupon->save();
            return Redirect::action('Admin\CuponController@index');
        }
    }
    public function eliminar($id){
        AdminCoupon::where('id',$id)->delete();
        return Redirect::action('Admin\CuponController@index');
    }
}
