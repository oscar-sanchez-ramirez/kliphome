<?php
Route::get('/', 'Admin\CuponController@index');
Route::get('nuevo-cupon','Admin\CuponController@nuevo');
Route::post('save','Admin\CuponController@save');
Route::post('eliminar/{id}','Admin\CuponController@eliminar');
