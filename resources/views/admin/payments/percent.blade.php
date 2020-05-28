@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card">
                <div class="card-header">Actulización de porcentaje general</div>
                <div class="card-body">

                    <form action="{{ url('') }}/categorias" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="control-label mb-1">Porcentaje Actual</label>
                            <input id="title" name="title" type="text" class="form-control" required value="{{ $general_percent->value }}">
                        </div>
                        <div class="form-group">
                            <label for="title" class="control-label mb-1">Precio de Visita</label>
                            <input id="visit_price" name="visit_price" type="number" class="form-control">
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
