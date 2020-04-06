@extends('layouts.app_admin')

@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title mb-3">Perfil del usuario</strong>
                    </div>
                    <div class="card-body">
                        <div class="mx-auto d-block">
                            <img class="rounded-circle mx-auto d-block" src="{{ $orden->clientName($orden->user_id)["avatar"] }}" alt="Card image cap">
                            <h5 class="text-sm-center mt-2 mb-1">{{ $orden->clientName($orden->user_id)["name"] }} {{ $orden->clientName($orden->user_id)["lastName"] }}</h5>
                            <div class="location text-sm-center">
                                <i class="fa fa-map-marker"></i> {{ $orden->clientAddress($orden->address)["alias"] }}, {{ $orden->clientAddress($orden->address)["address"] }}
                                @if($fixerman != null)
                                   @if($orden->state == 'PENDING' && $orden->state == 'FIXERMAN_NOTIFIED')
                                        @if($orden->price == "quotation" || $orden->state == "PENDING")
                                            <br><button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#quotationmodal">Cotizar</button>
                                            <form method="POST" action="{{ url('') }}/ordenes/notify/{{ $orden->id }}" style="display:inline-block" onsubmit="return confirm('Notificar al cliente sobre cotización')">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-bell"></i></button>
                                            </form>
                                        @endif
                                    @else
                                    <h4>Esperando confirmación del cliente</h4>
                                   @endif
                                @endif
                                @if($orden->price == "waitquotation")
                                    <h4>Cotización enviada</h4>
                                @endif
                                @if($orden->state == "FIXERMAN_NOTIFIED")
                                    <h4>Se notificó a técnicos</h4>
                                @endif
                            </div>
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
                            <strong class="card-title mb-3">Perfil Técnico del servicio @if($orden->state == 'PENDING' || $orden->state == 'FIXERMAN_NOTIFIED') (Pendiente)  @endif</strong>
                        </div>
                        <div class="card-body">
                            <div class="mx-auto d-block">
                                <img class="rounded-circle mx-auto d-block" src="{{ $fixerman->avatar }}" alt="Card image cap">
                                <h5 class="text-sm-center mt-2 mb-1">{{ $fixerman->name }} {{ $fixerman->lastName }}</h5>
                                <div class="location text-sm-center">
                                </div>
                            </div>
                            <hr>
                            <div class="card-text text-sm-center">
                                <i class="fa fa-envelope"></i> {{ $fixerman->email }}<br>
                                <i class="fa fa-phone"></i> {{ $fixerman->phone }}
                            </div>
                        </div>
                    </div>
                @else
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title mb-3">Técnico sin asignar</strong>
                    </div>
                    <div class="card-body">
                        <div class="mx-auto d-block">
                            <img class="rounded-circle mx-auto d-block" src="https://kliphome.com/img/profile.png" alt="Card image cap">
                        </div>
                        <hr>
                        <div class="card-text text-sm-center">
                            <button class="au-btn au-btn-icon au-btn--green au-btn--small" type="button" data-toggle="modal" data-target="#fixermanModal" id="fixermanModalButton" title="Ver" href="#" data-id="{{ $orden->id }}">
                                Asignar Técnico
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="card">
                    <img class="card-img-top" type="button" data-toggle="modal" data-target="#scrollmodal" src="{{ ($orden->service_image) }}" alt="Card image cap" >
                    <div class="card-body">
                        @php
                            $date = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $orden->service_date);
                            \Carbon\Carbon::setLocale('es');
                        @endphp
                        <h4 class="card-title mb-3">{{ \Carbon\Carbon::parse($date)->format('d,M H:i') }} / {{ $orden->type_service }} / {{ $orden->getService($orden->type_service,$orden->selected_id)["title"] }} /
                            @if($orden->state == "PENDING")
                                <span class="badge badge-danger">PENDIENTE DE COTIZACIÓN</span>
                            @elseif($orden->state == "FIXERMAN_NOTIFIED")
                                <span class="badge badge-info">TÉCNICOS NOTIFICADOS</span>
                            @elseif($orden->state == "ACCEPTED" || $orden->state == 'FIXERMAN_APPROVED')
                                <span class="badge badge-info">TÉCNICO ACEPTÓ SOLICITUD</span>
                            @elseif($orden->state == "CANCELLED")
                                <span class="badge badge-danger">CANCELADO</span>
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
<!-- fixerman modal -->
<div class="modal fade" id="fixermanModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scrollmodalLabel">Listado de Técnicos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-heigth:500px;overflow:scroll">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Nombres</th>
                            <th>Telefono</th>
                            <th>Servicios</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="tbodyModal">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>
<!-- end fixerman modal -->
<!-- modal quotation -->
<div class="modal fade" id="quotationmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scrollmodalLabel">Cotización</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-heigth:500px;overflow:scroll">
                <form class="au-form-icon" action="{{ url('') }}/ordenes/enviarCotizacion/{{ $orden->id }}" method="POST">
                    @csrf

                    <textarea name="solution" class="au-input au-input--full" cols="10" rows="3" placeholder="Explica la solución al problema"></textarea>
                    <textarea name="materials" class="au-input au-input--full" cols="10" rows="3" placeholder="Explica los materiales necesarios"></textarea>
                        <br><br>
                    <input class="au-input au-input--full au-input--h65" type="number" name="price" placeholder="Escribe un precio: Ejemplo:300">
                    <button class="au-input-icon" type="button"><i class="fa fa-dollar"></i></button><br><br><br>
                    <button type="submit" class="btn btn-primary">Enviar a {{ $orden->clientName($orden->user_id)["name"] }}</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal quotation -->
@include('layouts.modals.subCategoryModal');
@endsection
