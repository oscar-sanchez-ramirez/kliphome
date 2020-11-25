<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\AdminCoupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class CuponController extends Controller
{
    public function index(){
        $coupons = AdminCoupon::orderBy('id','DESC')->paginate(20);
        return view('admin.cupones.index')->with('coupons',$coupons);
    }
    public function search(Request $request){
        $key = $request->keywords;
        $users = User::where('name','LIKE','%'.$key.'%')->orWhere('lastName','LIKE','%'.$key.'%')->where('type','AppUser')->where('state',1)->get();
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
    public function nuevo(){
        return view('admin.cupones.new_coupon');
    }
    public function editar($id){
        $coupon = AdminCoupon::with('responsable')->where('id',$id)->first();
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
            $new_coupon->type = $request->type;
            $new_coupon->responsable = $request->responsable;
            $new_coupon->save();
            return response()->json([
                'success' => true,
                'message' => "Cupón creado",
            ]);
        }
    }
    public function update(Request $request){
        if($request->state == "true" || $request->state == 1){$state = 1;}else{$state=0;}
        AdminCoupon::where('id',$request->id)->update([
            'code' => $request->code,
            'discount' => $request->discount,
            'state' => $state,
            'type' => $request->type,
            'responsable' => $request->responsable
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
