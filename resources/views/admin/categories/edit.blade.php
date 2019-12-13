@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card">
                <div class="card-header">Creación de nueva Categoría</div>
                <div class="card-body">

                    <form action="{{ url('') }}/categorias/{{ $category->id }}" method="POST">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label for="title" class="control-label mb-1">Titulo</label>
                            <input id="title" name="title" type="text" class="form-control" required value="{{ $category->title }}">
                        </div>
                        <div class="form-group">
                            <label for="title" class="control-label mb-1">Precio de Visita</label>
                            <input id="visit_price" name="visit_price" type="number" class="form-control" value="{{ $category->visit_price }}">
                        </div>
                        <div>
                        <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                            <span id="payment-button-amount">Actualizar Categoría</span>
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection