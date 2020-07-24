@extends('layouts.app_admin')
@section('content')
<style>
    .au-btn--small{
        padding: 0 2px !important;
    }
    #danger{
      color:red;
    }
    #success{
      color:green;
    }
    #proccess{
      color:#1686FE;
    }
    #second{
      color:#3FC7FE;
    }
</style>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                    </div>
                    <div class="table-data__tool-right">
                        <a href="{{ url('') }}/ordenes/nueva-orden" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            Nueva Orden</a>
                    </div>
                </div>
                <div class="table-responsive table-responsive-data2">
                    @if(count($ordenes) == 0)
                        <div id="center">
                            <h4>No se regitraron órdenes en la aplicación</h4>
                        </div>
                    @else
                        <table class="table table-data2">
                            <thead>
                                <tr>

                                    <th>Client</th>
                                    <th>Categoría</th>
                                    <th>Fecha Registro</th>
                                    <th>Cotización</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ordenes as $orden)
                                    <tr class="tr-shadow">

                                        <td>{{ $orden->clientName($orden->user_id)["name"] }} {{ $orden->clientName($orden->user_id)["lastName"] }}</td>
                                        <td>{{ $orden->getCategory($orden->type_service,$orden->selected_id) }}</td>
                                        <td>{{ $orden->created_at->diffForHumans() }}</td>
                                        <td>{{ $orden->quotation($orden->id) }}</td>
                                        <td>
                                           @if($orden->state == "CANCELLED")
                                                <p id="danger">Cancelado</p>
                                           @elseif($orden->state == "QUALIFIED")
                                                <p id="success">Terminado</p>
                                           @elseif($orden->state == "ACCEPTED" || $orden->state == "FIXERMAN_APPROVED")
                                                <p id="proccess">Con Técnico</p>
                                           @elseif($orden->state == "FIXERMAN_NOTIFIED" || $orden->state == "PENDING")
                                                <p id="second">Sin Técnico</p>
                                           @elseif($orden->state == "FIXERMAN_DONE")
                                                <p id="second">Calificar</p>
                                           @endif
                                        </td>
                                        <td>
                                            <div class="table-data-feature">
                                                @if($orden->state == "CANCELLED")
                                                    <span class="status--denied">Cancelado</span>
                                                @else
                                                    <a class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="tooltip" data-placement="top" title="Ver" href="{{ url('') }}/ordenes/detalle-orden/{{ $orden->id }}">
                                                        Revisar
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
