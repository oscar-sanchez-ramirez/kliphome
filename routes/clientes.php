<?php

Route::get('/','Admin\UserController@index');
Route::get('busqueda','Admin\UserController@busqueda');
Route::get('busqueda_tecnico','Admin\UserController@busqueda_tecnico');
