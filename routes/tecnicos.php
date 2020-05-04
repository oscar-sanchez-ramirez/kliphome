<?php
Route::get('/', 'Admin\FixerManController@index');
Route::get('detalle/{id}','Admin\FixerManController@detail');
Route::post('guardar_ficha','Admin\FixerManController@guardar_ficha');
Route::get('listado','Admin\FixerManController@list');
Route::post('asignarTecnico/{id_tecnico}/{id_order}','Admin\FixerManController@asignarTecnico');
Route::get('aprove','Admin\FixerManController@aprove');
Route::post('updateFixermanImage','Admin\FixerManController@updateFixermanImage');