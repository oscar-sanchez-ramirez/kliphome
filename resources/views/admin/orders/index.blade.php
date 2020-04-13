@extends('layouts.app_admin')
@section('content')
<style>
    .au-btn--small{
        padding: 0 2px !important;
    }
</style>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive table-responsive-data2">
                    @if(count($ordenes) == 0)
                        <div id="center">
                            <h4>No se regitraron órdenes en la aplicación</h4>
                        </div>
                    @else
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="au-checkbox">
                                            <input type="checkbox">
                                            <span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Client</th>
                                    <th>Descripción</th>
                                    <th>Fecha de Atención</th>
                                    <th>Fecha Registro</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ordenes as $orden)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>{{ $orden->clientName($orden->user_id)["name"] }} {{ $orden->clientName($orden->user_id)["lastName"] }}</td>
                                        <td>{{ $orden->service_description }}</td>
                                        <td>{{ $orden->service_date }}</td>
                                        <td>{{ $orden->created_at->diffForHumans() }}</td>
                                        <td>
                                            <div class="table-data-feature">
                                                @if($order->state == "CANCELLED")
                                                <span class="status--denied">Cancelado</span>
                                                @else
                                                    <a class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="tooltip" data-placement="top" title="Ver" href="{{ url('') }}/ordenes/detalle-orden/{{ $orden->id }}">
                                                        Revisar Solicitud
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <!-- END DATA TABLE -->
            </div>
            {{ $ordenes->links() }}
        </div>
    </div>
</div>
@include('layouts.modals.subCategoryModal');
@endsection
