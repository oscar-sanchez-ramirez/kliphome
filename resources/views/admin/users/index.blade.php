@extends('layouts.app_admin')
@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <!-- DATA TABLE -->
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
                                <h3 class="title-2 tm-b-5">Registro de usuarios por mes</h3>
                                <br>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input id="start" name="cc-exp" type="tel" class="form-control cc-exp" value="" data-val="true" placeholder="YYYY / MM">
                                            <span class="help-block" data-valmsg-for="cc-exp" data-valmsg-replace="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input id="end" name="cc-exp" type="tel" class="form-control cc-exp" value="" data-val="true" placeholder="YYYY / MM">
                                            <span class="help-block" data-valmsg-for="cc-exp" data-valmsg-replace="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <button type="submit" class="btn btn-success btn-sm" onclick="filter()">
                                            <i class="fa fa-dot-circle-o"></i> Filtrar
                                        </button>
                                    </div>
                                </div>
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
                                    <th>Telefono</th>
                                    <th>Fecha Registro</th>
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
                                        <td>{{ $user->created_at->diffForHumans() }}</td>
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
function filter(){
    var url = window.location.origin+"/clientes";
    var start = $('#start').val();
    var end = $('#end').val();
    let val = validate(start,end);
    if(val){
        $.ajax({
            type: "GET",
            url: url,
            data: { 'start': start,'end': end, 'chart_query':"chart_query" },
            success: function(data) {
                var titles = [];
                var count_of_orders = [];
                var colors = [];
                for (let index = 0; index < data.length; index++) {
                    titles[index] = data[index]["x"];
                    count_of_orders[index] = data[index]["y"];
                    random = Math.floor(Math.random() * (255 - 0 + 1)) + 0;
                    colors[index] = "rgba("+random+",5,0, 0.3)";
                }
                $(".percent-chart").html('');
                $(".percent-chart").html('<canvas id="myChart2"></canvas>');
                open_chart(titles,count_of_orders,colors);
            },
            error: function(data) {
                alert("Fecha incorrecta, verifique sus datos");
            }
        });
    }else{
        alert("Fecha incorrecta, verifique sus datos");
    }
}
function show_chart(){
    if ($('.table-responsive').is(":visible") === false) {
        $(".table-responsive").show();
        $(".chart").hide();
    } else {
        $(".table-responsive").hide();
        $(".chart").show();
    }
    open_chart(@json($titles),@json($count_of_orders),@json($colors))
}
function open_chart(titles,count_of_orders,colors){
    var ctx = document.getElementById('myChart2').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: titles,
                datasets: [{
                    label: '# de usuarios',
                    data: count_of_orders,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            }
        });
}
function validate(start,end){
    if(start.length < 7 || end.length < 7){
        return false
    }return true;
}
</script>
@include('layouts.modals.subCategoryModal');
@endsection
