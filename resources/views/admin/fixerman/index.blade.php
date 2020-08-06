@extends('layouts.app_admin')
@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <div class="table-data__tool">
                    <div class="table-data__tool-right">
                        <button onclick="show_chart()" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-chart"></i></button>
                    </div>
                </div>
                <div class="chart" style="display: none">
                    <div class="col-lg-12">
                        <div class="au-card chart-percent-card">
                            <div class="au-card-inner">
                                <h3 class="title-2 tm-b-5">Registro de Técnicos por mes</h3>
                                <div class="row no-gutters">
                                    <div class="percent-chart">
                                        <canvas id="myChart2"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive table-responsive-data2">
                    @if(count($users) == 0)
                        <div id="center">
                            <h4>No se regitraron clientes en la aplicación</h4>
                        </div>
                    @else
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombres</th>
                                    <th>Email</th>
                                    <th># Telefono</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $current = ($users->currentPage() *10) -9;
                                $i = $current;
                            @endphp
                                @foreach ($users as $user)
                                    <tr class="tr-shadow">
                                        <td>{{$i++}}</td>
                                        <td>{{ $user->name }} {{ $user->lastName }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td id="state{{ $user->id }}">
                                            @if($user->state == 0)
                                                <span class="badge badge-danger" onclick="aproveFixerMan({{ $user->id }},'{{ $user->name }}')">Pendiente</span>
                                            @else
                                                <span class="badge badge-success">Validado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-data-feature">
                                                <a class="item" href="{{ url('') }}/tecnicos/detalle/{{ $user->id }}">
                                                    <i data-toggle="tooltip" data-placement="top" title="user" class="zmdi zmdi-eye"></i>
                                                </a>
                                                <button class="item" data-toggle="modal" data-target="#mediumImage" id="fixermanModalImage" data-id="{{ $user->avatar }}" data-user="{{ $user->id }}">
                                                    <i data-toggle="tooltip" data-placement="top" title="user" class="zmdi zmdi-image"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    @endif
                </div>
                <!-- END DATA TABLE -->
            </div>
        </div>
    </div>
</div>
@php
    $titles = [];
    $count_of_orders = [];
    $colors = [];
    $datasets = [];
    for ($i=0; $i < count($monthlysales); $i++) {
        $titles[$i] = $monthlysales[$i]["x"];
        $random = rand(0, 255);
        $colors[$i] = "rgba(".$random.",5,0, 0.3)";
        $count_of_orders[$i] = $monthlysales[$i]["y"];
    }
@endphp
<script>
function show_chart(){
    if ($('.table-responsive').is(":visible") === false) {
        $(".table-responsive").show();
        $(".chart").hide();
    } else {
        $(".table-responsive").hide();
        $(".chart").show();
    }
    var ctx = document.getElementById('myChart2').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($titles),
                datasets: [{
                    label: '# de técnicos',
                    data: @json($count_of_orders),
                    backgroundColor: @json($colors),
                    borderWidth: 1
                }]
            }
        });
}
</script>
@include('layouts.modals.fixermanModal');
@endsection
