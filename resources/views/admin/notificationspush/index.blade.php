@extends('layouts.app_admin')
@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <!-- DATA TABLE -->
                <div class="table-data__tool">
                    <div class="table-data__tool-left">

                    </div>
                    <div class="table-data__tool-right">
                        <a href="{{ url('') }}/notificaciones-push/create" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            Nuevo</a>
                    </div>
                </div>
                <div class="table-responsive table-responsive-data2">
                    @if(count($notificaciones) == 0)
                        <div id="center">
                            <h4>No tienes notificaciones</h4>
                        </div>
                    @else
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mensaje</th>
                                    <th>Audiencia</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $current = ($notificaciones->currentPage() *10) -9;
                                    $i = $current;
                                @endphp
                                @foreach ($notificaciones as $noti)
                                <tr class="tr-shadow">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $noti->message }}</td>
                                    <td>{{ $noti->audience }}</td>
                                    <td>{{ $noti->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <!-- END DATA TABLE -->
            </div>
            {{ $notificaciones->links() }}
        </div>
    </div>
</div>
@include('layouts.modals.subCategoryModal')
@endsection
