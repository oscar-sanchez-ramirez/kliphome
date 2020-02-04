@extends('layouts.app_admin')
@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <!-- DATA TABLE -->
                {{-- <div class="table-data__tool"> --}}
                    {{-- <div class="table-data__tool-left">
                        <div class="rs-select2--light rs-select2--md">
                            <select class="js-select2" name="property">
                                <option selected="selected">All</option>
                                <option value="">Option 1</option>
                                <option value="">Option 2</option>
                            </select>
                            <div class="dropDownSelect2"></div>
                        </div>
                    </div> --}}
                    {{-- <div class="table-data__tool-right">
                        <a href="{{ url('') }}/categorias/create" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>Categoría</a>
                        <div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
                            <select class="js-select2" name="type">
                                <option selected="selected">Exportar</option>
                                <option value="">Excel</option>
                                <option value="">PDF</option>
                            </select>
                            <div class="dropDownSelect2"></div>
                        </div>
                    </div> --}}
                {{-- </div> --}}
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
                                    <th>Client</th>
                                    <th>Descripción</th>
                                    <th>Fecha de Atención</th>
                                    <th>Fecha Registro</th>
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
                                        <td>{{ $i++ }} {{$noti->type }}</td>
                                        @if ($noti->type == "App\Notifications\NotifyAcceptOrder")
                                            <td><p>Un Técnico aceptó un trabajo</p></td>
                                            <td>{{ $noti->created_at->diffForHumans() }}</td>
                                            <td><a href="{{ url('') }}/ordenes/detalle-orden/{{ str_replace('"','',json_encode($noti->data["order_id"])) }}">Ver</a></td>
                                        @endif
                                        @if ($noti->type == "App\Notifications\Database\NewQuotation")
                                        <td><p>Una órden necesita una cotización</p></td>
                                        {{-- <td>{{ $noti->created_at->diffForHumans() }}</td> --}}
                                        <td><a href="{{ url('') }}/ordenes/detalle-orden/{{ str_replace('"','',json_encode($noti->data["order_id"])) }}">Cotizar</a></td>
                                        @else

                                        @endif
                                        {{--
                                        <td>{{ $order->created_at->diffForHumans() }}</td>
                                        <td>
                                            <div class="table-data-feature">
                                                <a class="item" data-toggle="tooltip" data-placement="top" title="Ver" href="{{ url('') }}/ordenes/detalle-orden/{{ $order->id }}">
                                                    <i class="zmdi zmdi-eye"></i>
                                                </a>
                                            </div>
                                        </td>--}}
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
