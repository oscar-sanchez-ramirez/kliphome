@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card" id="cardContent">
                <create-order-component :categories="{{ json_encode($categories) }}"></create-order-component>
            </div>
        </div>
    </div>
@endsection
