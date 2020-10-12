@extends('layouts.app_admin')
@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <!-- DATA TABLE -->

                <div class="table-responsive table-responsive-data2">
                    @if(count($reportes) == 0)
                        <div id="center">
                            <h4>No se regitraron reportes en la aplicación</h4>
                        </div>
                    @else
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombres</th>
                                    <th>Asunto</th>
                                    <th>Detalles</th>
                                    <th>Imagen</th>
                                    <th>Estado</th>
                                    <th>Fecha Registro</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="tbodyModal">
                                @php
                                    $current = ($reportes->currentPage() *10) -9;
                                    $i = $current;
                                @endphp
                                @foreach ($reportes as $reporte)
                                    <tr class="tr-shadow">
                                        <td>{{$i++}}</td>
                                        <td>{{ $reporte->user->name }} {{ $reporte->user->lastName }}</td>
                                        <td>{{ $reporte->asunto }}</td>
                                        <td>{{ $reporte->detalles }}</td>
                                        <td>
                                            @if($reporte->imagen != "")
                                                <img src="{{ $reporte->imagen }}" height="100px">
                                            @else
                                                <p>-</p>
                                            @endif
                                        </td>
                                        <td>{{ $reporte->estado }}</td>
                                        <td>{{ $reporte->created_at->diffForHumans() }}</td>
                                        <td>
                                            <div class="table-data-feature">
                                                @if($reporte->estado != 'RESUELTO')
                                                    <button class="item editar" type="button" data-id="{{ $reporte->id }}"><i data-toggle="tooltip" data-placement="top" class="zmdi zmdi-check"></i></button>
                                                    <form id="form-editar{{ $reporte->id }}" action="{{ url('') }}/reportes/{{ $reporte->id }}" method="POST">@csrf @method('PATCH')</form>
                                                @endif
                                                <button class="item eliminar" type="button" data-id="{{ $reporte->id }}"><i data-toggle="tooltip" data-placement="top" class="zmdi zmdi-delete"></i></button>
                                                <form id="form-eliminar{{ $reporte->id }}" action="{{ url('') }}/reportes/{{ $reporte->id }}" method="POST">@csrf @method('DELETE')</form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $reportes->links() }}
                    @endif
                </>
                <!-- END DATA TABLE -->
            </div>
        </div>
    </div>
</div>
@endsection
