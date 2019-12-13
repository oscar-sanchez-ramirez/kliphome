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
                @if($fixerman != null)
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title mb-3">Perfil Técnico del servicio</strong>
                    </div>
                    <div class="card-body">
                        <div class="mx-auto d-block">
                            <img class="rounded-circle mx-auto d-block" src="{{ url('') }}/images/icon/avatar-01.jpg" alt="Card image cap">
                            <h5 class="text-sm-center mt-2 mb-1">{{ $fixerman->name }} {{ $fixerman->lastName }}</h5>
                            <div class="location text-sm-center">
                                {{-- <i class="fa fa-map-marker"></i> {{ $orden->clientAddress($orden->address)["alias"] }}, {{ $orden->clientAddress($orden->address)["address"] }} --}}
                            </div>
                        </div>
                        <hr>
                        <div class="card-text text-sm-center">
                            <i class="fa fa-envelope"></i> {{ $fixerman->email }}<br>
                            <i class="fa fa-phone"></i> {{ $fixerman->phone }}
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-envelope"></i></button>
                        <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i></button>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="card">
                    <img class="card-img-top" type="button" data-toggle="modal" data-target="#scrollmodal" src="{{ ($orden->service_image) }}" alt="Card image cap" >
                    <div class="card-body">
                        <h4 class="card-title mb-3">{{ $orden->type_service }} - {{ $orden->getService($orden->type_service,$orden->selected_id)["title"] }}
                            @if($orden->state == "PENDING")
                                <span class="badge badge-danger">PENDIENTE</span>
                            @elseif($orden->state == "ACCEPTED")
                                <span class="badge badge-info">EN PROCESO</span>
                            @elseif($orden->state == "DONE")
                                <span class="badge badge-success">TERMINADO</span>
                            @endif
                        </h4>
                        <p class="card-text">
                            {{ $orden->service_description }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-12">

                <div class="table-responsive table-responsive-data2">

                </div>
                <!-- END DATA TABLE -->
            </div>
        </div>
    </div>
</div>

    <!-- modal scroll -->
			<div class="modal fade" id="scrollmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scrollmodalLabel">Imagen del servicio</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="max-heigth:500px;overflow:scroll">
                                <div>
                                    <img  src="{{ ($orden->service_image) }}" style="transform:rotate(90deg);" alt="Card image cap" >
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end modal scroll -->
@include('layouts.modals.subCategoryModal');
@endsection
