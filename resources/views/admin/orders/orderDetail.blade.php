@extends('layouts.app_admin')

@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="card">
            <div class="card-body">
                <div class="custom-tab">

                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="custom-nav-home-tab" data-toggle="tab" href="#custom-nav-home" role="tab" aria-controls="custom-nav-home"
                             aria-selected="true">Detalles</a>
                            <a class="nav-item nav-link" id="custom-nav-profile-tab" data-toggle="tab" href="#custom-nav-profile" role="tab" aria-controls="custom-nav-profile"
                             aria-selected="false">Técnico</a>
                            <a class="nav-item nav-link" id="custom-nav-contact-tab" data-toggle="tab" href="#custom-nav-contact" role="tab" aria-controls="custom-nav-contact"
                             aria-selected="false">Pagos</a>
                        </div>
                    </nav>
                    <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="custom-nav-home" role="tabpanel" aria-labelledby="custom-nav-home-tab">
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
                                                        @if($orden->state != 'PENDING' && $orden->state != 'FIXERMAN_NOTIFIED')
                                                            @if($orden->fixerman_arrive == "NO")
                                                                <h4>Técnico aun no llego al punto</h4>
                                                            @endif

                                                            @if($orden->price == "quotation" || $orden->price == "waitquotation")
                                                                <br><button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#quotationmodal">Cotizar</button>
                                                                {{-- <form method="POST" action="{{ url('') }}/ordenes/notify/{{ $orden->id }}" style="display:inline-block" onsubmit="return confirm('Notificar al cliente sobre cotización')">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-bell"></i></button>
                                                                </form> --}}
                                                            @endif
                                                        @else
                                                            <h4>Esperando confirmación del cliente</h4>
                                                        @endif
                                                        {{ $orden->pre_coupon }}
                                                        @if($orden->pre_coupon != "")
                                                            <div>
                                                                @php
                                                                    $coupon = $orden->orderCoupon($orden->pre_coupon);
                                                                @endphp
                                                                {{-- <b><i class="fas fa-ticket-alt"></i>Cupón Activo de {{ $coupon["discount"] }}% ({{ $coupon["code"] }})</b><br>
                                                                                Descuento --}}
                                                            </div>
                                                        @endif
                                                    @endif
                                                    {{-- @if($orden->price == "waitquotation")
                                                        <h4>Cotización enviada</h4>
                                                    @endif --}}
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="card-text text-sm-center">
                                                <i class="fa fa-envelope"></i> {{ $orden->clientName($orden->user_id)["email"] }}<br>
                                                <i class="fa fa-phone"></i> {{ $orden->clientName($orden->user_id)["phone"] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card">
                                        <img class="card-img-top" type="button" data-toggle="modal" data-target="#scrollmodal" src="{{ ($orden->service_image) }}" alt="Card image cap" >
                                        <div class="card-body">
                                            @php
                                                $date = \Carbon\Carbon::createFromFormat('Y/m/d H:i', $orden->service_date);
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
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-nav-profile" role="tabpanel" aria-labelledby="custom-nav-profile-tab">
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
                        <div class="tab-pane fade" id="custom-nav-contact" role="tabpanel" aria-labelledby="custom-nav-contact-tab">
                            <div class="table-responsive table--no-card m-b-30">
                                <table class="table table-borderless table-striped table-earning">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th>Cod. Pago</th>
                                            <th class="text-right">Monto</th>
                                            <th class="text-right">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($payments as $item)
                                            <tr>
                                                <td>{{ $item->created_at }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->code_payment }}</td>
                                                <td class="text-right">${{ $item->price }}</td>
                                                <td>
                                                    @if($item->state > 0)
                                                        <span class="status--process">Procesado</span>
                                                    @else
                                                        <span class="status--denied">Denegado</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <h5>No hay pagos</h5>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">


            </div>

            <div class="col-md-12">

                <div class="table-responsive table-responsive-data2">

                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.modals.subCategoryModal');
@include('layouts.modals.orderDetail');
@endsection
