<?php
Route::get('/', 'Admin\OrderController@index');
Route::get('detalle-orden/{id}','Admin\OrderController@orderDetail');
Route::post('aprobarSolicitudTecnico/{fixerman_id}/{order_id}','Admin\OrderController@aprobarSolicitudTecnico');
Route::post('eliminarSolicitudTecnico/{fixerman_id}/{order_id}','Admin\OrderController@eliminarSolicitudTecnico');
Route::post('enviarCotizacion/{order_id}','Admin\OrderController@enviarCotizacion');
Route::post('notify/{order_id}','Admin\ClientController@notify');