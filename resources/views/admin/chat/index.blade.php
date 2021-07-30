
@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card" id="cardContent">
                <messenger-component :user="{{ auth()->user() }}" :order="{{ intval($order) }}"></messenger-component>
            </div>
        </div>
    </div>
@endsection