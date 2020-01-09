
@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card">
                <messenger-component :user="{{ auth()->user() }}"></messenger-component>
            </div>
        </div>
    </div>
@endsection