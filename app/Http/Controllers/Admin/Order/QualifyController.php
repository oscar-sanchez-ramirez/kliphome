<?php

namespace App\Http\Controllers\Admin\Order;

use DB;
use App\Order;
use App\Qualify;
use App\SelectedOrders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QualifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $selected_order = SelectedOrders::where('id',$request->selected_order_id)->with('order')->first();
        if($selected_order->order->state == 'PENDING' || $selected_order->order->state == 'FIXERMAN_NOTIFIED' || $selected_order->order->state == 'FIXERMAN_APPROVED'){
            return response()->json([
                'success' => false,
                'message' => 'Esta orden esta pendiente o el técnico no ha marcado el trabajo como terminado'
            ]);
        }else{
            $qualify = new Qualify;
            $qualify->user_id = $request->user_id;
            $qualify->selected_order_id = $request->selected_order_id;
            $qualify->presentation = $request->presentation;
            $qualify->puntuality = $request->puntuality;
            $qualify->problemSolve = $request->problemSolve;
            $qualify->comment = $request->comment;
            $qualify->tip = 0;
            $qualify->save();
            return response()->json([
                'success' => true
            ]);
            Order::where('id',$selected_order->order->id)->update([
                'state' => 'QUALIFIED'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $qualifies = DB::table('orders as o')->join('selected_orders as so','so.order_id','o.id')->join('qualifies as q','q.selected_order_id','so.id')->select('q.*')->where('o.id',$id)->get();
        return response()->json([
            'qualifies' => $qualifies
          ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
