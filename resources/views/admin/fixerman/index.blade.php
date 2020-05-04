@extends('layouts.app_admin')
@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="row">
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
                    @if(count($users) == 0)
                        <div id="center">
                            <h4>No se regitraron clientes en la aplicación</h4>
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
                                    <th>Nombres</th>
                                    <th>Email</th>
                                    <th># Telefono</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>{{ $user->name }} {{ $user->lastName }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td id="state{{ $user->id }}">
                                            @if($user->state == 0)
                                            {{-- onclick="aproveFixerMan({{ $user->id }},'{{ $user->name }}')" --}}
                                            <button class="item" data-toggle="modal" data-target="#fichatecnica" id="fichatecnica" data-id="{{ $user->id }}">
                                                <span class="badge badge-danger" >Pendiente</span>
                                            </button>
                                            <button class="item" data-toggle="modal" data-target="#fichatecnica" id="fichatecnica" data-id="{{ $user->id }}">
                                                <i data-toggle="tooltip" data-placement="top" title="user" class="zmdi zmdi-eye"></i>
                                            </button>
                                            @else
                                            <button class="item" data-toggle="modal" data-target="#fichatecnica" id="fichatecnica" data-id="{{ $user->id }}">
                                                <span class="badge badge-success" >Validado</span>
                                            </button>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-data-feature">
                                                {{-- <button class="item" data-toggle="tooltip" data-placement="top" title="Delete">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button> --}}
                                                <button class="item" data-toggle="modal" data-target="#mediumModal" id="fixermanModal" data-id="{{ $user->id }}">
                                                    <i data-toggle="tooltip" data-placement="top" title="user" class="zmdi zmdi-eye"></i>
                                                </button>
                                                <button class="item" data-toggle="modal" data-target="#mediumImage" id="fixermanModalImage" data-id="{{ $user->avatar }}" data-user="{{ $user->id }}">
                                                    <i data-toggle="tooltip" data-placement="top" title="user" class="zmdi zmdi-image"></i>
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
@include('layouts.modals.fixermanModal');
@endsection
