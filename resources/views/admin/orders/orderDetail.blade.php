@extends('layouts.app_admin')

@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title mb-3">Perfil</strong>
                    </div>
                    <div class="card-body">
                        <div class="mx-auto d-block">
                            <img class="rounded-circle mx-auto d-block" src="{{ url('') }}/images/icon/avatar-01.jpg" alt="Card image cap">
                            <h5 class="text-sm-center mt-2 mb-1">{{ $orden->clientName($orden->user_id)["name"] }} {{ $orden->clientName($orden->user_id)["lastName"] }}</h5>
                            <div class="location text-sm-center">
                                <i class="fa fa-map-marker"></i> {{ $orden->clientAddress($orden->address)["alias"] }}, {{ $orden->clientAddress($orden->address)["address"] }}</div>
                        </div>
                        <hr>
                        <div class="card-text text-sm-center">
                            <i class="fa fa-envelope"></i> {{ $orden->clientName($orden->user_id)["email"] }}<br>
                            <i class="fa fa-phone"></i> {{ $orden->clientName($orden->user_id)["phone"] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img {{--  class="card-img-top" --}} src="{{ ($orden->service_image) }}" alt="Card image cap">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Card Image Title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                            content.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <!-- DATA TABLE -->
                {{-- <div class="table-data__tool"> --}}
                    {{-- <div class="table-data__tool-left">
                        <div class="rs-select2--light rs-select2--md">
                            <select class="js-select2" name="property">
                                <option selected="selected">All</option>
                                <option value="">Option 1</option>
                                <option value="">Option 2</option>
                            </select>
                            <div class="dropDownSelect2"></div>
                        </div>
                    </div> --}}
                    {{-- <div class="table-data__tool-right">
                        <a href="{{ url('') }}/categorias/create" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>Categoría</a>
                        <div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
                            <select class="js-select2" name="type">
                                <option selected="selected">Exportar</option>
                                <option value="">Excel</option>
                                <option value="">PDF</option>
                            </select>
                            <div class="dropDownSelect2"></div>
                        </div>
                    </div> --}}
                {{-- </div> --}}
                <div class="table-responsive table-responsive-data2">

                </div>
                <!-- END DATA TABLE -->
            </div>
        </div>
    </div>
</div>
@include('layouts.modals.subCategoryModal');
@endsection
