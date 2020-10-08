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
                                    <th>Estado</th>
                                    <th>Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody class="tbodyModal">
                                @php
                                    $current = ($users->currentPage() *10) -9;
                                    $i = $current;
                                @endphp
                                @foreach ($reportes as $reporte)
                                    <tr class="tr-shadow">
                                        <td>{{$i++}}</td>
                                        <td>{{ $reporte->user->name }} {{ $user->user->lastName }}</td>
                                        <td>{{ $user->asunto }}</td>
                                        <td>{{ $user->detalles }}</td>
                                        <td>{{ $user->estado }}</td>
                                        <td>{{ $user->created_at->diffForHumans() }}</td>
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
