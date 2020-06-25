<?php
Route::get('/', 'Admin\FixerManController@index');
Route::get('detalle/{id}','Admin\FixerManController@detail1');
Route::get('reviews/{id}','Admin\FixerManController@reviews');
Route::get('payments/{id}','Admin\FixerManController@payments');
Route::post('guardar_datos/{id}','Admin\FixerManController@guardar_datos');
// Route::get('detalle','Admin\FixerManController@detail1');
Route::post('guardar_ficha','Admin\FixerManController@guardar_ficha');
Route::get('listado','Admin\FixerManController@list');
Route::post('asignarTecnico/{id_tecnico}/{id_order}','Admin\FixerManController@asignarTecnico');
Route::post('eliminarTecnico/{id_tecnico}/{id_order}','Admin\FixerManController@eliminarTecnico');
Route::get('aprove','Admin\FixerManController@aprove');
Route::post('updateFixermanImage','Admin\FixerManController@updateFixermanImage');

// axios
Route::get('ordenes_tecnico/{id}','Admin\FixerManController@orders');
Route::get('calcular','Admin\FixerManController@calcular');
