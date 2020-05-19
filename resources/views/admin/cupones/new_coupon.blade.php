@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        @if(Session::has('failed'))
            <div class="alert alert-danger" role="alert">
                El cupón ya existe
            </div>
        @endif
        <div class="section__content section__content--p30">
            <div class="card">
                <div class="card-header">Creación de un nuevo Cupón</div>
                <div class="card-body">

                    <form action="{{ url('') }}/cupones/save" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="code" class="control-label mb-1">Código</label>
                            <input id="code" name="code" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="discount" class="control-label mb-1">Descuento (%)</label>
                            <input id="discount" name="discount" type="number" class="form-control" required>
                        </div>
                        <div>
                        <div>
                            <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                <span id="payment-button-amount">Guardar Cupón</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
