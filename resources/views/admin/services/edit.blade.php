@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card">
                <div class="card-header">Creación de nuevo Servicio</div>
                <div class="card-body">

                    <form action="{{ url('') }}/servicios/{{ $service->id }}" method="POST">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label for="title" class="control-label mb-1">Titulo del Servicio</label>
                            <input id="title" name="title" type="text" class="form-control" required value="{{ $service->title }}">
                        </div>
                        <div class="row form-group">
                            <div class="col-12">
                                <label for="title" class="control-label mb-1">Categoría - SubCategoría</label>
                                <select name="category_id" id="select" class="form-control" required>
                                    @foreach ($categories as $category)
                                        @if($category->id == $service->subcategory_id)
                                            <option value="{{ $category->id }}">{{ $category->CategoryName($category->category_id)["title"] }} - {{ $category->title }}</option>
                                        @endif
                                    @endforeach
                                    @foreach ($categories as $category)
                                        @if($category->id != $service->subcategory_id)
                                            <option value="{{ $category->id }}">{{ $category->CategoryName($category->category_id)["title"] }} - {{ $category->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                <span id="payment-button-amount">Guardar Servicio</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection