<?php

namespace App\Exports;

use DB;
use App\Order;
use App\Quotation;
use App\SelectedOrders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection,WithHeadings
{
    protected $key;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($key)
    {
        $this->key = $key;
    }

    public function headings(): array
    {
        return [
            'id',
            'Nombre',
            'Apellidos',
            'Email',
            'Telefono',
            '$ Visita',
            'Fecha del Servicio',
            'Descripcion del Servicio'
        ];
    }

    public function collection()
    {

        switch ($this->key) {
            case 'con_tecnico':
                $fixerman = SelectedOrders::where('state',1)->pluck('order_id');
                return DB::table('orders as o')->join('users as u','o.user_id','u.id')->whereIn('o.id',$fixerman)->select('o.id','u.name','u.lastName','u.email','u.phone','o.visit_price','o.service_date','o.service_description')->orderBy('o.id','DESC')->get();
                // return Order::select(['user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereIn('id',$fixerman)->orderBy('id','DESC')->get();
            case 'tecnico_llego':
                return DB::table('orders as o')->join('users as u','o.user_id','u.id')->where('fixerman_arrive',"SI")->select('o.id','u.name','u.lastName','u.email','u.phone','o.visit_price','o.service_date','o.service_description')->orderBy('o.id','DESC')->get();
                // return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('fixerman_arrive',"SI")->orderBy('id','DESC')->get();
            case 'contizacion_pendiente':
                $quotations = Quotation::where('state',0)->pluck('order_id');
                return DB::table('orders as o')->join('users as u','o.user_id','u.id')->whereIn('o.id',$quotations)->select('o.id','u.name','u.lastName','u.email','u.phone','o.visit_price','o.service_date','o.service_description')->orderBy('o.id','DESC')->get();
                // return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereIn('id',$quotations)->orderBy('id','DESC')->get();
            case 'cotizacion_pagada':
                $quotations = Quotation::where('state',1)->pluck('order_id');
                return DB::table('orders as o')->join('users as u','o.user_id','u.id')->whereIn('o.id',$quotations)->select('o.id','u.name','u.lastName','u.email','u.phone','o.visit_price','o.service_date','o.service_description')->orderBy('o.id','DESC')->get();
                // return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereIn('id',$quotations)->orderBy('id','DESC')->get();
            case 'sin_cotizacion':
                $quotations = Quotation::where('state',1)->pluck('order_id');
                return DB::table('orders as o')->join('users as u','o.user_id','u.id')->whereNotIn('o.id',$quotations)->select('o.id','u.name','u.lastName','u.email','u.phone','o.visit_price','o.service_date','o.service_description')->orderBy('o.id','DESC')->get();
                // return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->whereNotIn('id',$quotations)->orderBy('id','DESC')->get();
            case 'terminados':
                return DB::table('orders as o')->join('users as u','o.user_id','u.id')->where('o.state',"FIXERMAN_DONE")->select('o.id','u.name','u.lastName','u.email','u.phone','o.visit_price','o.service_date','o.service_description')->orderBy('o.id','DESC')->get();
                // return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('state',"FIXERMAN_DONE")->orderBy('id','DESC')->get();
            case 'calificados':
                return DB::table('orders as o')->join('users as u','o.user_id','u.id')->where('o.state',"QUALIFIED")->select('o.id','u.name','u.lastName','u.email','u.phone','o.visit_price','o.service_date','o.service_description')->orderBy('o.id','DESC')->get();
                // return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->where('state',"QUALIFIED")->orderBy('id','DESC')->get();
            case 'todos':
                return DB::table('orders as o')->join('users as u','o.user_id','u.id')->select('o.id','u.name','u.lastName','u.email','u.phone','o.visit_price','o.service_date','o.service_description')->orderBy('io.idd','DESC')->get();
                // return Order::select(['id','user_id','service_description','service_date','state','type_service','selected_id','fixerman_arrive','created_at'])->orderBy('id','DESC')->get();
            default:
                # code...
                break;
        }
    }
}
