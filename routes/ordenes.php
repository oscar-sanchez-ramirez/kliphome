<?php
Route::get('/', 'Admin\OrderController@index');
Route::get('detalle-orden/{id}','Admin\OrderController@orderDetail');
Route::get('detalle_usuario/{id}/{address}','Admin\OrderController@detalle_usuario');
Route::get('cupon/{cupon}','Admin\OrderController@cupon');
Route::get('getService/{type}/{id}','Admin\OrderController@getService');
Route::get('cotizaciones/{order_id}','Admin\OrderController@cotizaciones');
Route::post('aprobarSolicitudTecnico/{fixerman_id}/{order_id}','Admin\OrderController@aprobarSolicitudTecnico');
Route::post('eliminarSolicitudTecnico/{fixerman_id}/{order_id}','Admin\OrderController@eliminarSolicitudTecnico');
Route::post('enviarCotizacion/{order_id}','Admin\OrderController@enviarCotizacion');
Route::post('notify/{order_id}','Admin\OrderController@notify');
Route::post('cancelOrder/{order_id}','Admin\OrderController@cancelOrder');
Route::post('markDone/{order_id}','Admin\OrderController@markDone');
Route::post('cancelarCotizacion/{quotation_id}','Admin\OrderController@cancelarCotizacion');
