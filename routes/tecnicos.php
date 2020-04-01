<?php
Route::get('/', 'Admin\FixerManController@index');
Route::get('detalle/{id}','Admin\FixerManController@detail');
Route::get('listado','Admin\FixerManController@list');
Route::get('aprove','Admin\FixerManController@aprove');
Route::post('updateFixermanImage','Admin\FixerManController@updateFixermanImage');