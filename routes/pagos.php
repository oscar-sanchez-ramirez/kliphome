<?php
Route::get('/', 'Admin\PaymentController@index');
Route::get('porcentaje-general','Admin\PaymentController@percent');
Route::post('actualizar-porcentaje','Admin\PaymentController@update_percent');
