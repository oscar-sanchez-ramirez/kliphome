@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card">
                <div class="card-header">Actulización de porcentaje general</div>
                <div class="card-body">

                    <form action="{{ url('') }}/pagos/actualizar-porcentaje" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="control-label mb-1">Porcentaje Actual</label>
                            <input id="title" name="value" type="text" class="form-control" required value="{{ $general_percent->value }}">
                        </div>
                        <div class="form-group">
                            <label for="title" class="control-label mb-1">Selecciona una opción</label>
                            <select class="form-control" name="options" required>
                                <option selected="selected"></option>
                                <option value="1">Actualizar para todos</option>
                                <option value="2">Actualizar en adelante</option>
                            </select>
                        </div>
                        <div>
                        <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                            <span id="payment-button-amount">Actualizar</span>
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
