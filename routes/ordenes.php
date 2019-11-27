<?php
Route::get('/', 'Admin\OrderController@index');
Route::get('detalle-orden/{id}','Admin\OrderController@orderDetail');