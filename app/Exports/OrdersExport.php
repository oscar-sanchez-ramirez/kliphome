<?php

namespace App\Exports;

use App\Order;
use App\Quotation;
use App\SelectedOrders;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdersExport implements FromCollection
{
    protected $key;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($key)
    {
        $this->key = $key;

    }

    public function collection()
    {

        switch ($this->key) {
            case 'con_tecnico':
                $fixerman = SelectedOrders::where('state',1)->pluck('order_id');
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereIn('id',$fixerman)->orderBy('id','DESC')->get();
            case 'tecnico_llego':
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('fixerman_arrive',"SI")->orderBy('id','DESC')->get();
            case 'contizacion_pendiente':
                $quotations = Quotation::where('state',0)->pluck('order_id');
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereIn('id',$quotations)->orderBy('id','DESC')->get();
            case 'cotizacion_pagada':
                $quotations = Quotation::where('state',1)->pluck('order_id');
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereIn('id',$quotations)->orderBy('id','DESC')->get();
            case 'sin_cotizacion':
                $quotations = Quotation::where('state',1)->pluck('order_id');
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereNotIn('id',$quotations)->orderBy('id','DESC')->get();
            case 'terminados':
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('state',"FIXERMAN_DONE")->orderBy('id','DESC')->get();
            case 'calificados':
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('state',"QUALIFIED")->orderBy('id','DESC')->get();
            case 'todos':
                return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->orderBy('id','DESC')->get();
            default:
                # code...
                break;
        }
    }
}
