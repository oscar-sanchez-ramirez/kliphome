@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card">
                <div class="card-header">Crear Notificación</div>
                <div class="card-body">

                    <form action="{{ url('') }}/notificaciones-push" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="control-label mb-1">Mensaje (Máximo 120 caracteres)</label>
                            <input id="title" name="title" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="title" class="control-label mb-1">Segmento:</label>
                            <select name="segmento" class="form-control">
                                <option value="todos">Todos</option>
                                <option value="sin_registro">Sin Registro</option>
                                <option value="con_orden">Con al menos una órden</option>
                                <option value="sin_orden">Sin Órdenes</option>
                            </select>
                        </div>
                        <div class="row form-group">
                            <div class="col-12">
                                <div class="form-check">
                                    <div class="checkbox">
                                        <label for="checkbox1" class="form-check-label ">
                                            <input type="checkbox" name="clientes" value="clientes" class="form-check-input">Clientes
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label for="checkbox2" class="form-check-label ">
                                            <input type="checkbox" name="tecnicos" value="tecnicos" class="form-check-input">Tecnicos
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                <span id="payment-button-amount">Enviar Notificación</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
