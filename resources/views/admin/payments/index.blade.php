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
                <div class="table-data__tool">
                    <button onclick="show_chart()" class="au-btn au-btn-icon au-btn--green">
                        <i class="zmdi zmdi-chart"></i></button>
                    <div class="table-data__tool-right">
                        <a href="{{ url('') }}/pagos/pagos-fecha" class="au-btn au-btn-icon au-btn--green">
                            Pagos por fecha</a>
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
                                        <canvas id="myChart2" style="height: 100%; width:100%"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                                            <div class="col-md-6">
                                                                Porcentaje de {{ $payment->name }} {{ $payment->lastName }} : {{ $payment->percent }}%
                                                            </div>
                                                            <div class="col-md-6">
                                                                Ganancia: {{ ($payment->workforce * $payment->percent) / 100 }}
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
                                                <p id="danger">Denegado</p>
                                           @else
                                                <p id="success">Aceptado</p>
                                           @endif
                                        </td>
                                        <td>
                                            <div class="table-data-feature">
                                                @if($payment->state == 0)
                                                    <span class="status--denied">Denegado</span>
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
{{-- @php
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
@endphp --}}
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
    open_chart()
}
function open_chart(titles,count_of_orders,colors){
    myChart2 = document.getElementById('myChart2'),
    context2 = myChart2.getContext('2d');
    window.addEventListener('resize', resizeCanvas, false);
    resizeCanvas();
    var dates = @json($stats);
    console.log(dates);
    for (let index = 0; index < dates.length; index++) {
            if(dates[index]["data"] != null){
                dates[index]["data"].sort(function(a,b){
                    return a.x > b.x;
                })
            }
        }
    var ctx = document.getElementById('myChart2').getContext('2d');
    var myChart = new Chart(ctx, {
            type: 'line',
            data: { datasets:dates },
            options: {
                tooltips: { mode: 'index',intersect: false },
                hover: { mode: 'nearest', intersect: true },
                scales: {
                    xAxes: [ {
                        display: true,
                        type: 'time',
                        time: {
                        parser: 'YYYY/MM/DD',
                        unit: 'day',
                        unitStepSize: 10,
                        displayFormats: {
                            'day': 'YYYY/MM/DD'
                        }
                        }
                    }
                    ],
                yAxes: [{
                    ticks: {
                    beginAtZero:true
                    }
                }]
                },
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
@endsection
