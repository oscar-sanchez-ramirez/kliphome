<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Payment extends Model
{
    public function detalle_pago($id,$price){
        $cotizacion = Quotation::where('order_id',$id)->get();

        $tecnico = DB::table('selected_orders as so')->join('users as u','u.id','so.user_id')->join('fixerman_stats as f','f.user_id','u.id')
        ->select('f.percent','u.name','u.lastName')->where('so.order_id',$id)->first();
        if(count($cotizacion)>1){
            foreach($cotizacion as $cot){
                $visit_price = Payment::where('order_id',$id)->where('description','VISITA')->first();
                if(!$visit_price){
                    $visit_price = 0;
                }else{
                    $visit_price = $visit_price->price;
                }
                if(((($cot->price + $cot->workforce) == $price) || (($cot->price + $cot->workforce - $visit_price) == $price)) && $cot->state == 1){
                    $cotizacion = Quotation::where('id',$cot->id)->first();
                }
            }
            return response()->json([
                'order' => $id,
                'cotizacion' => $cotizacion,
                'tecnico' => $tecnico
            ]);
        }else{
            return response()->json([
                'order' => $id,
                'cotizacion' => $cotizacion[0],
                'tecnico' => $tecnico
            ]);
        }


    }
}
