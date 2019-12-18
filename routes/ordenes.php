<?php
Route::get('/', 'Admin\OrderController@index');
Route::get('detalle-orden/{id}','Admin\OrderController@orderDetail');
Route::post('aprobarSolicitudTecnico/{fixerman_id}/{order_id}','Admin\OrderController@aprobarSolicitudTecnico');
Route::post('eliminarSolicitudTecnico/{fixerman_id}/{order_id}','Admin\OrderController@eliminarSolicitudTecnico');