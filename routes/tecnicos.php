<?php
Route::get('/', 'Admin\FixerManController@index');
Route::get('detalle/{id}','Admin\FixerManController@detail');
Route::get('aprove','Admin\FixerManController@aprove');
Route::post('updateFixermanImage','admin\FixerManController@updateFixermanImage');