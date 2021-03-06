@extends('layouts.app_admin')
@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-12">
                <!-- DATA TABLE -->
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                        <div class="rs-select2--light rs-select2--md">
                            <select class="js-select2" name="property">
                                <option selected="selected">All Categories</option>
                                <option value="">Option 1</option>
                                <option value="">Option 2</option>
                            </select>
                            <div class="dropDownSelect2"></div>
                        </div>
                    </div>
                    <div class="table-data__tool-right">
                        <a href="{{ url('') }}/categorias/create" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>Categoría</a>
                        {{-- <div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
                            <select class="js-select2" name="type">
                                <option selected="selected">Exportar</option>
                                <option value="">Excel</option>
                                <option value="">PDF</option>
                            </select>
                            <div class="dropDownSelect2"></div>
                        </div> --}}
                        <button class="item" data-toggle="modal" data-target="#staticModal">
                            <i data-toggle="tooltip" data-placement="top" title="Info" class="fa fa-question-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive table-responsive-data2">
                    @if(count($categories) == 0)
                        <div id="center">
                            <h4>No se regitraron categorías</h4>
                        </div>
                    @else
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="au-checkbox">
                                            <input type="checkbox">
                                            <span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Titulo</th>
                                    <th>Sub-Categorías</th>
                                    <th>Precio de Visita</th>
                                    <th>Fecha Creación</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>{{ $category->title }}</td>
                                        <td>{{ $category->subCategoriesCount($category->id) }}</td>
                                        <td>{{ $category->visit_price }}</td>
                                        <td>{{ $category->created_at->diffForHumans() }}</td>
                                        <td>
                                            <div class="table-data-feature">
                                                <form action="{{ url('') }}/categorias/{{ $category->id }}" type="GET">
                                                    <button class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </button>
                                                </form>
                                                <button class="item" data-toggle="modal" data-target="#mediumModal" id="SubcategoryModal" data-title="{{ $category->title }}" data-id="{{ $category->id }}">
                                                    <i data-toggle="tooltip" data-placement="top" title="SubCategorias" class="zmdi zmdi-collection-item-2"></i>
                                                </button>
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
        </div>
    </div>
</div>
@include('layouts.modals.subCategoryModal');
@endsection
