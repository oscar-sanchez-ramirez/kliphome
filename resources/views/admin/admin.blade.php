@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">

                <div class="row m-t-25">
                    <div class="col-sm-6 col-lg-3">
                        <a href="{{ url('') }}/clientes">
                            <div class="overview-item overview-item--c1">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="zmdi zmdi-account-o"></i>
                                        </div>
                                        <div class="text">
                                            <h2>{{ $clientes }}</h2>
                                            <span>Clientes</span>
                                        </div>
                                    </div>
                                    <div class="overview-chart">
                                        <canvas id="widgetChart1"></canvas>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a href="{{ url('') }}/tecnicos">
                            <div class="overview-item overview-item--c2">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="zmdi zmdi-wrench"></i>
                                        </div>
                                        <div class="text">
                                            <h2>{{ $tecnicos }}</h2>
                                            <span>Técnicos</span>
                                        </div>
                                    </div>
                                    <div class="overview-chart">
                                        <canvas id="widgetChart2"></canvas>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a href="{{ url('') }}/ordenes">
                            <div class="overview-item overview-item--c3">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="zmdi zmdi-calendar-note"></i>
                                        </div>
                                        <div class="text">
                                            <h2>{{ $ordenes }}</h2>
                                            <span>Órdenes</span>
                                        </div>
                                    </div>
                                    <div class="overview-chart">
                                        <canvas id="widgetChart3"></canvas>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c4">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="zmdi zmdi-money"></i>
                                    </div>
                                    <div class="text">
                                        <h2>${{ $pagos }}</h2>
                                        <span>ganados</span>
                                    </div>
                                </div>
                                <div class="overview-chart">
                                    <canvas id="widgetChart4"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="au-card chart-percent-card">
                            <div class="au-card-inner">
                                <h3 class="title-2 tm-b-5">Total de pedidos por Categoría</h3>
                                <div class="row no-gutters">

                                        <div class="percent-chart">
                                            <canvas id="myChart"></canvas>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="copyright">
                            <p>Copyright © 2020 <a href="{{ url('') }}/https://789.mx">789mx</a>.. All rights reserved 789mx</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $titles = [];
        $count_of_orders = [];
        $colors = [];
        for ($i=0; $i < count($categories); $i++) {
            $titles[$i] = $categories[$i]["title"];
            $random = rand(0, 255);
            $colors[$i] = "rgba(".$random.",5,0, 0.3)";
            if(isset($categories[$i]["count"])){
                $count_of_orders[$i] = $categories[$i]["count"];
            }else{
                $count_of_orders[$i] = 0;
            }
        }
    @endphp
    <script>
        window.onload = function() {
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($titles),
                datasets: [{
                    label: '# de ordenes',
                    data: @json($count_of_orders),
                    backgroundColor: @json($colors),
                    borderWidth: 1
                }]
            }
        });
        };
        </script>
        <style>
            #myChart{
                width: 100% !important;
            }
        </style>
@endsection
