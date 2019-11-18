@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card">
                <div class="card-header">Creación de nueva Categoría</div>
                <div class="card-body">

                    <form action="{{ url('') }}/categorias" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="control-label mb-1">Titulo</label>
                            <input id="title" name="title" type="text" class="form-control" required>
                        </div>
                        <div>
                            <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                <span id="payment-button-amount">Guardar Categoría</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection