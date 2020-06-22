@extends('layouts.app')
<style>
    #mainNav .nav-link{
        color:black !important;
    }#mainNav{
        background-color: transparent !important;
    }
</style>
@section('content')
  <header class="masthead">
      <div style="position: relative; z-index: 2;">

          <div class="row" style="text-align: center;padding-top:25%">
            <div class="col-md-4"></div>
            <div class="col-md-4"><h2>¡ERROR!</h2><h2>No se encontró a usuario, intente con otro</h2><h2>Gracias por usar KlipHome</h2></div>
            <div class="col-md-4"></div>

          </div>
      </div>
  </header>
@endsection
