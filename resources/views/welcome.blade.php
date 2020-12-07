@extends('layouts.app')
<style>
    #mainNav .nav-link{
        color:black !important;
    }#mainNav{
        background-color: transparent !important;
    }
    .btn-group-fab {
        z-index: 100;
        position: fixed;
        width: 50px;
        height: auto;
        right: 80px; bottom: 40px;
      }

      .btn-group-fab .btn {
        background: transparent;
        border:none !important;
        outline: none !important;
        box-shadow: none !important;
        width: 60px;
        height: 60px;
      }

</style>
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
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Carpintería</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Cerrajería</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Electricidad</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Electrodomésticos</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Pintura</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Plomería</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Computadora</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Celular</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Fumigación</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Sanitización</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Mil Usos</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Impermeabilización</button>
                        <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="dropdown-item white buttom_nav_item" id="color1" type="button">Vidrios y Canceles</button>
                    </div>
                  </div>
            </div>
            <div class="col-md-4">
                <img src="{{ url('') }}/img/item2.png" height="267px" width="130px"><br>
                <button onclick="location.href='https://apps.apple.com/us/app/kliphome/id1490914291?ls=1'" target="blank" class="button buttom_item" id="color2">Descarga la App</button>
            </div>
            <div class="col-md-4">
                <img src="{{ url('') }}/img/item3.png" height="200px" width="200px"><br>
                <button onclick="location.href='https://apps.apple.com/us/app/kliphome-tecnicos/id1490914489?ls=1'" class="button buttom_item" id="color3">¡A trabajar!</button>
            </div>
          </div>
      </div>
  </header>
  {{--  <div class="btn-group-fab" role="group" aria-label="FAB Menu">
    <div>
       <button onclick="window.location.href='https://api.whatsapp.com/send?phone=+525568013183'" class="btn" type="button"><img src="{{ url('')}}/img/whatsapp.png" ></button>
    </div>
  </div>  --}}
  <script async data-id="66488" src="https://cdn.widgetwhats.com/script.min.js"></script>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-HS794P6RXD"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-HS794P6RXD');
</script>
@endsection
