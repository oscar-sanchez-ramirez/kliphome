<?php
Route::get('/', 'Admin\OrderController@index');
// Route::get('qualifies/{id}','Admin\OrderController@qualifies');
Route::get('detalle-orden/{id}','Admin\OrderController@orderDetail');
Route::get('detalle_usuario/{id}/{address}','Admin\OrderController@detalle_usuario');
Route::get('cupon/{cupon}','Admin\OrderController@cupon');
Route::get('getService/{type}/{id}','Admin\OrderController@getService');
Route::get('cotizaciones/{order_id}','Admin\OrderController@cotizaciones');
Route::get('nueva-orden','Admin\OrderController@nueva_orden');
Route::get('busqueda','Admin\OrderController@busqueda');
Route::get('export','Admin\OrderController@export');
Route::post('store','Admin\OrderController@store');
Route::post('nuevo-pago/{id}','Admin\OrderController@nuevo_pago');
Route::post('aprobarSolicitudTecnico/{fixerman_id}/{order_id}','Admin\OrderController@aprobarSolicitudTecnico');
Route::post('eliminarSolicitudTecnico/{fixerman_id}/{order_id}','Admin\OrderController@eliminarSolicitudTecnico');
Route::post('enviarCotizacion/{order_id}','Admin\OrderController@enviarCotizacion');
Route::post('notify/{order_id}','Admin\OrderController@notify');
Route::post('cancelOrder/{order_id}','Admin\OrderController@cancelOrder');
Route::post('markDone/{order_id}','Admin\OrderController@markDone');
Route::post('cancelarCotizacion/{quotation_id}','Admin\OrderController@cancelarCotizacion');
Route::post('confimarCotizacion/{quotation_id}','Admin\OrderController@confimarCotizacion');

Route::resource('comments', 'Admin\Order\CommentController');
Route::resource('qualify','Admin\Order\QualifyController');
