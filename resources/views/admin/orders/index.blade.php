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
      color:#63c76a;
    }
    #proccess{
      color:#1686FE;
    }
    #second{
      color:#3FC7FE;
    }#warning{
        color:#ffc107;
    }#secondary{
        color:#6c757d;
    }.zmdi-badge-check{
        font-size: 20px;
    }
</style>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                        <div class="rs-select2--light rs-select2--md">
                            <select class="js-select2" name="property" id="filter_orders">
                                <option selected="selected" value="all">Filtros</option>
                                <option value="todos">Todos</option>
                                <option value="con_tecnico">Con técnico</option>
                                <option value="tecnico_llego">Técnico llegó</option>
                                <option value="sin_cotizacion">Sin Cotización</option>
                                <option value="contizacion_pendiente">Cotización Pendiente</option>
                                <option value="cotizacion_pagada">Cotización Pagada</option>
                                <option value="terminados">Terminados</option>
                                <option value="calificados">Calificados</option>
                            </select>
                            <div class="dropDownSelect2"></div>
                        </div>
                        <div class="rs-select2--light">
                            <form class="form-header" action="" method="POST">
                                <input class="au-input au-input--xl" type="text" name="search" id="search_cliente_orden" placeholder="Buscar cliente...">
                            </form>
                        </div>
                        <button onclick="show_chart()" class="au-btn au-btn--green"><i class="zmdi zmdi-chart"></i></button>
                        <button onclick="export_excel('{{ Request::get('filtro') }}')" class="au-btn au-btn-icon au-btn--green"><i class="zmdi zmdi-file-text"></i></button>
                    </div>
                    <div class="table-data__tool-right">
                        <a href="{{ url('') }}/ordenes/nueva-orden" class="au-btn au-btn-icon au-btn--green">
                            Nueva Orden</a>
                        <button class="item" data-toggle="modal" data-target="#steporder">
                            <i data-toggle="tooltip" data-placement="top" title="Info" class="fa fa-question-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="chart" style="display: none">
                    <div class="col-lg-12">
                        <div class="au-card chart-percent-card">
                            <div class="au-card-inner">
                                <h3 class="title-2 tm-b-5">Registro de órdenes por fecha</h3>
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
                                        <canvas id="myChart2" style="height: 100%; width:100%"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive table-responsive-data2">
                    @if(count($ordenes) == 0)
                        <div id="center">
                            <h4>No se regitraron órdenes en la aplicación</h4>
                        </div>
                    @else
                        <table class="table table-data2">
                            <thead>
                                <tr>

                                    <th>Cliente</th>
                                    <th>Categoría</th>
                                    <th>Fecha Registro</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="tabla_order">
                                @foreach ($ordenes as $orden)
                                    <tr class="tr-shadow">
                                        <td>{{ $orden->clientName($orden->user_id)["name"] }} {{ $orden->clientName($orden->user_id)["lastName"] }}</td>
                                        <td>{{ $orden->getCategory($orden->type_service,$orden->selected_id) }}</td>

                                        <td>{{ $orden->created_at->diffForHumans() }}</td>
                                        <td>
                                            <i class="zmdi zmdi-badge-check" id="{{ $orden->fixerman($orden->id) }}"></i>
                                            @if($orden->fixerman_arrive === 'SI')
                                                <i class="zmdi zmdi-badge-check" id="success"></i>
                                            @else
                                                <i class="zmdi zmdi-badge-check" id="secondary"></i>
                                            @endif
                                            <i class="zmdi zmdi-badge-check" id="{{ $orden->quotation($orden->id) }}"></i>
                                            @if($orden->state === 'FIXERMAN_DONE' || $orden->state === 'QUALIFIED')
                                                <i class="zmdi zmdi-badge-check" id="success"></i>
                                            @else
                                                <i class="zmdi zmdi-badge-check" id="secondary"></i>
                                            @endif
                                            @if($orden->state === 'QUALIFIED')
                                                <i class="zmdi zmdi-badge-check" id="success"></i>
                                            @else
                                                <i class="zmdi zmdi-badge-check" id="secondary"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-data-feature">
                                                @if($orden->state == "CANCELLED")
                                                    <span class="status--denied">Cancelado</span>
                                                @else
                                                    <a class="au-btn au-btn--green" data-toggle="tooltip" data-placement="top" title="Ver" href="{{ url('') }}/ordenes/detalle-orden/{{ $orden->id }}">
                                                        Revisar
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

            @if((Request::get('filtro') == '' || Request::get('filtro') == 'todos') && Request::get('usuario') == '')
                {{ $ordenes->links() }}
            @endif

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
        {{--  //open_chart(@json($titles),@json($count_of_orders),@json($colors))  --}}
    }
    function show_stats(){
        filter('todos');
    }
    function filter(estado){
        var start = $('#start').val();
        var end = $('#end').val();
        let val = validate(start,end);
        if(!val && estado != 'todos'){
            alert("Fecha incorrecta, verifique sus datos");
           return;
        }
        var url = window.location.origin+"/ordenes";
        $.ajax({
            type: "GET",
            url: url,
            data: { 'start': start,'end': end, 'chart_query':estado },
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
                alert("Fecha incorrecta, verifique sus datos1");
            }
        });
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
                        label: '# de órdenes',
                        data: count_of_orders,
                        backgroundColor: colors,
                        borderWidth: 1
                    }]
                }
            });
    }
    function validate(start,end){
        if(start.length < 10 || end.length < 10){
            return false
        }return true;
    }
    function resizeCanvas() {
        myChart2.width = window.innerWidth;
        myChart2.height = window.innerHeight;
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
