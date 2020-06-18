<?php
Route::get('/', 'Admin\CuponController@index');
Route::get('nuevo-cupon','Admin\CuponController@nuevo');
Route::get('editar/{id}','Admin\CuponController@editar');
Route::post('save','Admin\CuponController@save');
Route::post('update','Admin\CuponController@update');
Route::post('eliminar/{id}','Admin\CuponController@eliminar');
