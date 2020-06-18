@extends('layouts.app_admin')
@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="card" id="cardContent">
                <editcoupon-component :coupon="{{ $coupon }}"></editcoupon-component>
            </div>
        </div>
    </div>
@endsection
