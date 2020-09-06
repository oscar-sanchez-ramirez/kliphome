@extends('layouts.app_admin')
@section('content')
@php
    $titles = [];
    $count_of_orders = [];
    $colors = [];
    $datasets = [];
    $total_usuarios = 0;
    for ($i=0; $i < count($monthlysales); $i++) {
        $titles[$i] = $monthlysales[$i]["x"];
        $random = rand(0, 255);
        $colors[$i] = "rgba(".$random.",5,0, 0.3)";
        $count_of_orders[$i] = $monthlysales[$i]["y"];
        $total_usuarios = $total_usuarios + $monthlysales[$i]["y"];
    }
@endphp
<link href="{{ url('') }}/vendor/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
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
                                            <label for="dtp_input2" class="col-md-2 control-label">Inicio</label>
                                            <div class="input-group date form_date" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                <input class="form-control cc-exp" size="16" id="start" type="text" >
                                                <span class="input-group-addon" ><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                            <input type="hidden" id="dtp_input2" value="" /><br/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="dtp_input2" class="col-md-2 control-label">Fin</label>
                                            <div class="input-group date form_date2" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                <input class="form-control cc-exp" size="16" id="end" type="text" >
                                                <span class="input-group-addon" ><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                            <input type="hidden" id="dtp_input2" value="" /><br/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="dtp_input2" class="col-md-2 control-label"></label>

                                            <div class="input-group">
                                                <br>
                                                <button type="submit" class="btn btn-success btn-sm" onclick="filter()">
                                                    <i class="fa fa-dot-circle-o"></i> Filtrar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-gutters">
                                    <div class="total"></div>
                                    <div class="percent-chart">
                                        <h3>Total:{{ $total_usuarios }}</h3>
                                        <canvas id="myChart2" style="height: 100%; width:100%"></canvas>
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
                var total = 0;
                for (let index = 0; index < data.length; index++) {
                    titles[index] = data[index]["x"];
                    count_of_orders[index] = data[index]["y"];
                    total = total + data[index]["y"];
                    random = Math.floor(Math.random() * (255 - 0 + 1)) + 0;
                    colors[index] = "rgba("+random+",5,0, 0.3)";
                }
                $(".total").html('<h3>Total:'+total+'</h3>');
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
    console.log(count_of_orders);
    myChart2 = document.getElementById('myChart2'),
    context2 = myChart2.getContext('2d');
    window.addEventListener('resize', resizeCanvas, false);
    resizeCanvas();

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
function resizeCanvas() {
    myChart2.width = window.innerWidth;
    myChart2.height = window.innerHeight;
}
function validate(start,end){
    if(start.length < 7 || end.length < 7){
        return false
    }return true;
}


</script>
@include('layouts.modals.subCategoryModal');
<script type="text/javascript" src="{{ url('') }}/vendor/jquery/jquery.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="{{ url('') }}/vendor/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{ url('') }}/vendor/bootstrap/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="{{ url('') }}/vendor/bootstrap/js/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>
<script>
    $( document ).ready(function() {
        $.noConflict();
        $('.form_date').datetimepicker({
            language:  'es',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
        $('.form_date2').datetimepicker({
            language:  'es',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
    });
</script>
@endsection
