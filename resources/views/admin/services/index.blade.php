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
                            <select class="js-select2" name="property" id="filterCategories">
                                <option selected="selected" value="all">Categorías</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                            <div class="dropDownSelect2"></div>
                        </div>
                    </div>
                    <div class="table-data__tool-right">
                        <a href="{{ url('') }}/servicios/create" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>Servicio</a>
                        <button class="item" data-toggle="modal" data-target="#staticModal">
                            <i data-toggle="tooltip" data-placement="top" title="Info" class="fa fa-question-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                            <tr>
                                <th>
                                    <label class="au-checkbox">
                                        <input type="checkbox">
                                        <span class="au-checkmark"></span>
                                    </label>
                                </th>
                                <th>Categoría</th>
                                <th>Sub-Categoría</th>
                                <th>Servicio</th>
                                <th>Fecha Creación</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="bodyServices">
                            @foreach ($services as $service)
                                <tr class="tr-shadow">
                                    <td>
                                        <label class="au-checkbox">
                                            <input type="checkbox">
                                            <span class="au-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>{{ $service->category }}</td>
                                    <td>{{ $service->subcategory }}</td>
                                    <td><b>{{ $service->title }}</b></td>
                                    <td>{{ \Carbon\Carbon::parse($service->created_at)->diffForHumans() }}</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <form action="{{ url('') }}/servicios/{{ $service->id }}" type="GET">
                                                <button class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>
                                            </form>
                                            <button class="item" data-toggle="modal" data-target="#mediumModal" id="SubServiceModal" data-title="{{ $service->title }}" data-id="{{ $service->id }}">
                                                <i data-toggle="tooltip" data-placement="top" title="SubServicios" class="zmdi zmdi-collection-item-3"></i>
                                            </button>
                                            <form action="{{ url('') }}/servicios/{{ $service->id }}" method="POST">
                                                @method('DELETE') @csrf
                                                <button class="item" data-toggle="tooltip" type="submit" data-placement="top" title="Eliminar">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="spacer"></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- END DATA TABLE -->
                {{ $services->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.modals.subServiceModal');
@endsection
