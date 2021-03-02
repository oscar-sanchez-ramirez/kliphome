@extends('layouts.app_admin')
@section('content')
<link href="{{ url('') }}/vendor/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
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
                                <h3 class="title-2 tm-b-5">Registro de pagos por fecha</h3>
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
                                                <button type="submit" class="btn btn-success btn-sm" onclick="filter('dates')">
                                                    <i class="fa fa-dot-circle-o"></i> Filtrar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-gutters">
                                    <div class="total"></div>
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
                                    <th>#</th>
                                    <th>Concepto</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $current = ($payments->currentPage() *10) -9;
                                    $i = $current;
                                @endphp
                                @foreach ($payments as $payment)
                                    @if($payment != [])
                                    <tr class="tr-shadow">
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            @if($payment->description == "PAGO POR SERVICIO")
                                                @php
                                                    $detalle_pago = $payment->detalle_pago($payment->order_id,$payment->price);
                                                @endphp
                                                <a data-toggle="collapse" href="#collapsePayment{{ $payment->id }}" role="button" aria-expanded="false" aria-controls="collapsePayment{{ $payment->id }}">
                                                    {{ $payment->description }}
                                                </a>
                                                <div class="collapse" id="collapsePayment{{ $payment->id }}">
                                                <div class="card card-body">
                                                    <div class="card card-body">
                                                        <div class="row">
                                                            <b>Precio por material:  </b>{{ json_encode($detalle_pago->original["cotizacion"]["price"]) }}
                                                        </div>
                                                        <div class="row">
                                                            <b>Mano de Obra:  </b>{{ json_encode($detalle_pago->original["cotizacion"]["workforce"]) }}
                                                        </div>
                                                        <div class="row" id="rowPercent{{ $payment->id }}">
                                                            <div class="col-md-6">
                                                                Porcentaje de
                                                                @if($detalle_pago->original["tecnico"] != null)
                                                                    {{ json_encode($detalle_pago->original["tecnico"]->name ) }} {{ json_encode($detalle_pago->original["tecnico"]->lastName) }} : {{ json_encode($detalle_pago->original["tecnico"]->percent) }}%
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                @php
                                                                    if($detalle_pago->original["tecnico"] != null){$percent = json_encode(intval($detalle_pago->original['tecnico']->percent));}else{$percent = 100;}
                                                                @endphp
                                                                Ganancia:{{ (json_encode(intval($detalle_pago->original['cotizacion']['workforce'])) * $percent) / 100 }}
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
                                                    <a class="au-btn au-btn--green" data-toggle="tooltip" data-placement="top" title="Ver" href="{{ url('') }}/ordenes/detalle-orden/{{ $payment->order_id }}">
                                                        Ver Orden
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <!-- END DATA TABLE -->
            </div>
            {{ $payments->links() }}
        </div>
    </div>
</div>
<script>
function show_chart(){
    if ($('.table-responsive').is(":visible") === false) {
        $(".table-responsive").show();
        $(".chart").hide();
    } else {
        $(".table-responsive").hide();
        $(".chart").show();
        show_stats();
    }
}
function show_stats(){
    filter('all');
}

function filter(estado){
    var start = $('#start').val();
    var end = $('#end').val();
    let val = validate(start,end);
    if(!val && estado != 'all'){
        alert("Fecha incorrecta, verifique sus datos");
       return;
    }
    var url = window.location.origin+"/pagos";
    console.log(estado);
    $.ajax({
        type: "GET",
        url: url,
        data: { 'start': start,'end': end, 'chart_query':estado },
        success: function(data) {
            $(".percent-chart").html('');
            $(".percent-chart").html('<canvas id="myChart2"></canvas>');
            open_chart(data);
        },
        error: function(data) {
            alert("Fecha incorrecta, verifique sus datos1");
        }
    });
}
function open_chart(dates){
    myChart2 = document.getElementById('myChart2'),
    context2 = myChart2.getContext('2d');
    window.addEventListener('resize', resizeCanvas, false);
    resizeCanvas();

    for (let index = 0; index < dates.length; index++) {
        if(dates[index]["data"] != null){
            dates[index]["data"].sort(function(a,b){
                return a.x > b.x;
            })
        }
    }
    $(".total").html('<h3>Total Visita + Mano de Obra:$'+dates[0]["total"]+'</h3><h3>Total Pago por Material: $'+dates[1]["total"]+'</h3>');
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
    if(start.length < 10 || end.length < 10){
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
