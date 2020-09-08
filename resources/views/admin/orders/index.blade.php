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
    #proccess{
      color:#1686FE;
    }
    #second{
      color:#3FC7FE;
    }
</style>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                        <div class="table-data__tool">
                                <button onclick="show_chart()" class="au-btn au-btn-icon au-btn--green">
                                    <i class="zmdi zmdi-chart"></i></button>
                        </div>
                    </div>
                    <div class="table-data__tool-right">
                        <a href="{{ url('') }}/ordenes/nueva-orden" class="au-btn au-btn-icon au-btn--green">
                            Nueva Orden</a>
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

                                    <th>Client</th>
                                    <th>Categoría</th>
                                    <th>Fecha Registro</th>
                                    <th>Cotización</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ordenes as $orden)
                                    <tr class="tr-shadow">

                                        <td>{{ $orden->clientName($orden->user_id)["name"] }} {{ $orden->clientName($orden->user_id)["lastName"] }}</td>
                                        <td>{{ $orden->getCategory($orden->type_service,$orden->selected_id) }}</td>
                                        <td>{{ $orden->created_at->diffForHumans() }}</td>
                                        <td>{{ $orden->quotation($orden->id) }}</td>
                                        <td>
                                           @if($orden->state == "CANCELLED")
                                                <p id="danger">Cancelado</p>
                                           @elseif($orden->state == "QUALIFIED")
                                                <p id="success">Terminado</p>
                                           @elseif($orden->state == "ACCEPTED" || $orden->state == "FIXERMAN_APPROVED")
                                                <p id="proccess">Con Técnico</p>
                                           @elseif($orden->state == "FIXERMAN_NOTIFIED" || $orden->state == "PENDING")
                                                <p id="second">Sin Técnico</p>
                                           @elseif($orden->state == "FIXERMAN_DONE")
                                                <p id="second">Calificar</p>
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
            {{ $ordenes->links() }}
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
        }
        {{--  //open_chart(@json($titles),@json($count_of_orders),@json($colors))  --}}
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
</script>
@include('layouts.modals.subCategoryModal');
@endsection
