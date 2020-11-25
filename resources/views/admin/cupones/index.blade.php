@extends('layouts.app_admin')
@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                        <div class="rs-select2--light rs-select2--md">
                            {{-- <select class="js-select2" name="property">
                                <option selected="selected">All Categories</option>
                                <option value="">Option 1</option>
                                <option value="">Option 2</option>
                            </select>
                            <div class="dropDownSelect2"></div> --}}
                        </div>
                    </div>
                    <div class="table-data__tool-right">
                        <a href="{{ url('') }}/cupones/nuevo-cupon" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>Nuevo Cupon</a>
                        {{-- <div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
                            <select class="js-select2" name="type">
                                <option selected="selected">Exportar</option>
                                <option value="">Excel</option>
                                <option value="">PDF</option>
                            </select>
                            <div class="dropDownSelect2"></div>
                        </div> --}}
                    </div>
                </div>
                <div class="table-responsive table-responsive-data2">
                    @if(count($coupons) == 0)
                        <div id="center">
                            <h4>No se regitraron cupones</h4>
                        </div>
                    @else
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th>
                                       #
                                    </th>
                                    <th>Código</th>
                                    <th>Disponibilidad</th>
                                    <th>Descuento</th>
                                    <th>Cantidad de usos</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $current = ($coupons->currentPage() *20) -19;
                                    $i = $current;
                                @endphp
                                @foreach ($coupons as $coupon)
                                    <tr class="tr-shadow">
                                        <td>
                                            {{ $i }}
                                        </td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>
                                            @if($coupon->state == "1")
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                            <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->type == 'Porcentaje')
                                                {{ $coupon->discount }}%
                                            @else
                                                ${{ $coupon->discount }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $coupon->count($coupon->code) }}
                                            {{-- @if($coupon->is_charged == "N")
                                                -
                                            @else
                                                {{ $coupon->getUser($coupon->user_id)["name"] }} {{ $coupon->getUser($coupon->user_id)["lastName"] }}
                                            @endif --}}
                                        </td>
                                        <td>
                                            {{-- @if($coupon->is_charged == "Y")
                                                <a class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="tooltip" data-placement="top" title="Ver" href="{{ url('') }}/ordenes/detalle-orden/{{ $coupon->order_id }}">
                                                    Revisar Solicitud
                                                </a>
                                            @else --}}
                                                <div class="table-data-feature">
                                                    <a href="{{ url('') }}/cupones/editar/{{ $coupon->id }}" class="item">
                                                        <i data-toggle="tooltip" data-placement="top" title="SubCategorias" class="zmdi zmdi-edit"></i>
                                                    </a>
                                                    @if($coupon->count($coupon->code) == 0)
                                                    <form action="{{ url('') }}/cupones/eliminar/{{ $coupon->id }}" method="POST">
                                                        @csrf
                                                        <button class="item">
                                                            <i data-toggle="tooltip" data-placement="top" title="SubCategorias" class="zmdi zmdi-delete"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $coupons->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.modals.fixermanModal');
@endsection
