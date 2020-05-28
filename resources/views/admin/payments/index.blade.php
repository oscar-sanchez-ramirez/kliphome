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
                    <h5>Porcentaje general : <a href="{{ url('') }}/pagos/porcentaje-general">{{ $general_percent->value }}%</a></h5>
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
                                        <td>
                                            @if($payment->description == "PAGO POR SERVICIO")
                                            <a data-toggle="collapse" href="#collapsePayment{{ $payment->id }}" role="button" aria-expanded="false" aria-controls="collapsePayment{{ $payment->id }}">
                                                {{ $payment->description }}
                                            </a>
                                            <div class="collapse" id="collapsePayment{{ $payment->id }}">
                                                <div class="card card-body">
                                                    <div class="card card-body">
                                                        <div class="row">
                                                            <b>Precio por servicio:  </b>{{ $payment->service_price }}
                                                        </div>
                                                        <div class="row">
                                                            <b>Mano de Obra:  </b>{{ $payment->workforce }}
                                                        </div>
                                                        <div class="row" id="rowPercent{{ $payment->id }}">
                                                            <div class="col-md-4">
                                                                Calcular %:
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="number" id="percent{{ $payment->id }}" class="form-control">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button class="btn btn-success" onclick="calculatePercent({{ $payment->id }},{{ $payment->workforce }}">Calcular</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                              </div>
                                            @else
                                            {{ $payment->description }}
                                            @endif
                                        </td>
                                        <td>{{ $payment->price }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->diffForHumans() }}</td>
                                        <td>
                                           @if($payment->state == 0)
                                                <p id="danger">Cancelado</p>
                                           @else
                                                <p id="success">Aceptado</p>
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
