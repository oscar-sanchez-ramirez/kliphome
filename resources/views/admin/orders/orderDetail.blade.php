@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card" id="cardContent">
                <order-component :orden="{{ json_encode($orden) }}" :fixerman="{{ json_encode($fixerman) }}" :payments="{{ json_encode($payments) }}"></order-component>
            </div>
        </div>
    </div>
@endsection
