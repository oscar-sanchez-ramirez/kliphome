<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function detalle_pago($id){
        $cotizacion = Quotation::where('order_id',$id)->first();
        $tecnico = DB::table('selected_orders as so')->join('users as u','u.id','so.user_id')->join('fixerman_stats as f','f.user_id','u.id')
        ->select('f.percent','u.name','u.lastName')->where('so.order_id',$id)->first();
        return response()->json([
            'order' => $id,
            'cotizacion' => $cotizacion,
            'tecnico' => $tecnico
        ]);
    }
}
