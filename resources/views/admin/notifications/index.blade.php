@extends('layouts.app_admin')
@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <!-- DATA TABLE -->
                <div class="table-responsive table-responsive-data2">
                    @if(count($notifications) == 0)
                        <div id="center">
                            <h4>No tienes notificaciones</h4>
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
                                    <th>#</th>
                                    <th>Descripción</th>
                                    <th>Fecha</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1 @endphp
                                @foreach (Auth::user()->notifications as $noti)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>{{ $i++ }}</td>
                                        @if ($noti->type == "App\Notifications\NotifyAcceptOrder")
                                            <td><p>Un Técnico aceptó un trabajo</p></td>
                                            <td>{{ $noti->created_at->diffForHumans() }}</td>
                                            <td><a href="{{ url('') }}/ordenes/detalle-orden/{{ str_replace('"','',json_encode($noti->data["order_id"])) }}">Ver</a></td>
                                        @endif
                                        @if ($noti->type == "App\Notifications\Database\NewQuotation")
                                            <td><p>Una órden necesita una cotización</p></td>
                                            <td>{{ $noti->created_at->diffForHumans() }}</td>
                                            <td><a href="{{ url('') }}/ordenes/detalle-orden/{{ str_replace('"','',json_encode($noti->data["id"])) }}">Cotizar</a></td>
                                        @endif
                                        @if($noti->type == "App\Notifications\Database\NoFixermanForOrder")
                                            <td><p>No hay técnicos para esta orden</p></td>
                                            <td>{{ $noti->created_at->diffForHumans() }}</td>
                                            <td><a href="{{ url('') }}/ordenes/detalle-orden/{{ str_replace('"','',json_encode($noti->data["id"])) }}">Ver Orden</a></td>
                                        @endif
                                        @if($noti->type == "App\Notifications\Database\QuotationCancelled")
                                            <td><p>El usuario no aceptó la tarifa</p></td>
                                            <td>{{ $noti->created_at->diffForHumans() }}</td>
                                            <td><a href="{{ url('') }}/ordenes/detalle-orden/{{ str_replace('"','',json_encode($noti->data["id"])) }}">Ver Orden</a></td>
                                        @endif
                                        @if($noti->type == "App\Notifications\NewFixerMan")
                                            <td><p>Un nuevo técnico se registró - {{ str_replace('"','',json_encode($noti->data["name"])) }} {{ str_replace('"','',json_encode($noti->data["lastName"])) }}</p></td>
                                            <td>{{ $noti->created_at->diffForHumans() }}</td>
                                            <td><a href="{{ url('') }}/tecnicos">Ver</a></td>
                                        @endif
                                        @if($noti->type == "App\Notifications\Admin\Report")
                                            <td><p>Tienes un nuevo reporte desde la aplicación</p></td>
                                            <td>{{ $noti->created_at->diffForHumans() }}</td>
                                            <td><a href="{{ url('') }}/reportes">Ver</a></td>
                                        @endif
                                    </tr>
                                    <tr class="spacer"></tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <!-- END DATA TABLE -->
            </div>
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@include('layouts.modals.subCategoryModal');
@endsection
