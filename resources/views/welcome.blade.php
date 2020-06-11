@extends('layouts.app')

@section('content')
  <header class="masthead">
      <div style="position: relative; z-index: 2;">
          <div class="row" style="text-align: center;padding-top:5%;margin-bottom:7%">
            <div class="col-md-4" style="margin:auto">
                <div style="text-align: center;">
                    <img src="{{ url('') }}/img/icon.png" alt="" height="90px" width="90px"><br>
                    <div style="padding-top:3%">
                        <h1 class="mx-auto my-0 text-uppercase" id="fs-20">KlipHome</h1>
                        <h5 id="subtutle">Servicios del hogar desde la palma de tu mano</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4" style="text-align:center">
                <div style="text-align:center;">
                    <h4>¡Descarga la App!</h4>
                    <a href="https://play.google.com/store/apps/details?id=com.sonmx.KlipHomeClient" target="blank"><img src="{{ url('') }}/img/android.png"  width="155px" height="45px"></a>
                    <a href="https://apps.apple.com/us/app/kliphome/id1490914291?ls=1" target="blank"><img src="{{ url('') }}/img/apple.png" width="135px" height="45px"></a>
                </div>
            </div>
            <div class="col-md-4"></div>
          </div>
          <div class="row" style="text-align: center">
            <div class="col-md-4">
                <img src="{{ url('') }}/img/item1.png" height="200px" width="200px"><br>
                {{-- <button class="buttom_item" id="color1">Necesito un servicio</button> --}}
                <div class="btn-group dropup">
                    <button id="color1" type="button" class="buttom_item" id="color1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Necesito un servicio
                    </button>
                    <div class="dropdown-menu">
                      <!-- Dropdown menu links -->
                        <button class="dropdown-item white buttom_nav_item" id="color1" type="button">Carpintería</button>
                        <button class="dropdown-item white buttom_nav_item" id="color1" type="button">Cerrajería</button>
                        <button class="dropdown-item white buttom_nav_item" id="color1" type="button">Electricidad</button>
                        <button class="dropdown-item white buttom_nav_item" id="color1" type="button">Electrodomésticos</button>
                        <button class="dropdown-item white buttom_nav_item" id="color1" type="button">Pintura</button>
                        <button class="dropdown-item white buttom_nav_item" id="color1" type="button">Plomería</button>
                        <button class="dropdown-item white buttom_nav_item" id="color1" type="button">Computadora</button>
                        <button class="dropdown-item white buttom_nav_item" id="color1" type="button">Celular</button>
                    </div>
                  </div>
            </div>
            <div class="col-md-4">
                <img src="{{ url('') }}/img/item2.png" height="267px" width="130px"><br>
                <a href="https://apps.apple.com/us/app/kliphome/id1490914291?ls=1" target="blank" class="buttom_item" id="color2">Descarga la App</a>
            </div>
            <div class="col-md-4">
                <img src="{{ url('') }}/img/item3.png" height="200px" width="200px"><br>
                <a href="https://apps.apple.com/us/app/kliphome-tecnicos/id1490914489?ls=1" class="buttom_item" id="color3">¡A trabajar!</a>
            </div>
          </div>
      </div>
  </header>
@endsection
