<?php
Route::get('/', 'Admin\FixerManController@index');
Route::get('detalle/{id}','Admin\FixerManController@detail');
Route::get('ficha_tecnica/{id}','Admin\FixerManController@ficha_tecnica');
Route::get('listado','Admin\FixerManController@list');
Route::post('asignarTecnico/{id_tecnico}/{id_order}','Admin\FixerManController@asignarTecnico');
Route::get('aprove','Admin\FixerManController@aprove');
Route::post('updateFixermanImage','Admin\FixerManController@updateFixermanImage');