<?php

namespace App\Http\Controllers\Admin;

use App\AdminCoupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class CuponController extends Controller
{
    public function index(){
        $coupons = AdminCoupon::orderBy('id','DESC')->paginate(10);
        return view('admin.cupones.index')->with('coupons',$coupons);
    }
    public function nuevo(){
        return view('admin.cupones.new_coupon');
    }
    public function editar($id){
        $coupon = AdminCoupon::where('id',$id)->first();
        return view('admin.cupones.edit_coupon')->with('coupon',$coupon);
    }
    public function save(Request $request){
        $check_coupon = AdminCoupon::where('code',$request->code)->first();
        if($check_coupon){
            return response()->json([
                'success' => false,
                'message' => "El cupón ya existe",
            ]);
        }else{
            if($request->state == "true"){$state = 1;}else{$state=0;}
            $new_coupon = new AdminCoupon;
            $new_coupon->code = $request->code;
            $new_coupon->discount = $request->discount;
            $new_coupon->state = $state;
            $new_coupon->save();
            return response()->json([
                'success' => true,
                'message' => "Cupón creado",
            ]);
        }
    }
    public function update(Request $request){
        if($request->state == "true"){$state = 1;}else{$state=0;}
        AdminCoupon::where('id',$request->id)->update([
            'code' => $request->code,
            'discount' => $request->discount,
            'state' => $state
        ]);
        return response()->json([
            'success' => true,
            'message' => "Cupón actualizado",
        ]);
    }
    public function eliminar($id){
        AdminCoupon::where('id',$id)->delete();
        return Redirect::action('Admin\CuponController@index');
    }
}
