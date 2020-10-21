<?php

Route::get('/','Admin\UserController@index');
Route::get('export','Admin\UserController@export');
Route::get('busqueda','Admin\UserController@busqueda');
Route::get('busqueda_tecnico','Admin\UserController@busqueda_tecnico');
