
@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card" id="cardContent">
                <fixerman-component :fixerman="{{ $fixerman }}" :ficha="{{ $ficha }}" :delegation="{{ $delegation }}" :categories="{{ $categories }}"></fixerman-component>
            </div>
        </div>
    </div>
@endsection
