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
</style>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive table-responsive-data2">
                    @if(count($payments) == 0)
                        <div id="center">
                            <h4>No se regitraron órdenes en la aplicación</h4>
                        </div>
                    @else
                        <table class="table table-data2">
                            <thead>
                                <tr>

                                    <th>Concepto</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr class="tr-shadow">

                                        <td>{{ $payment->description }}</td>
                                        <td>{{ $payment->price }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->diffForHumans() }}</td>
                                        <td>
                                           @if($payment->state == 0)
                                                <p id="danger">Cancelado</p>
                                           @else
                                                <p id="success">Terminado</p>
                                           @endif
                                        </td>
                                        <td>
                                            <div class="table-data-feature">
                                                @if($payment->state == 0)
                                                    <span class="status--denied">Cancelado</span>
                                                @else
                                                    <a class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="tooltip" data-placement="top" title="Ver" href="{{ url('') }}/ordenes/detalle-orden/{{ $payment->order_id }}">
                                                        Revisar Orden
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
            {{-- {{ $payments->links() }} --}}
        </div>
    </div>
</div>
@include('layouts.modals.subCategoryModal');
@endsection
