<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin', function () {
    return view('admin.admin');
});

Route::resource('categorias', 'CategoryController');
Route::resource('servicios', 'ServiceController',['except' => ['show']]);
Route::resource('sub-categorias', 'SubCategoryController',['except' => ['show']]);

Route::post('sub-categorias/nuevo','SubCategoryController@nuevo');
Route::get('getSubcategory/{category_id}','SubCategoryController@getSubservice');

Route::post('sub-servicios/nuevo','SubServiceController@nuevo');
Route::get('getSubservice/{service_id}','SubServiceController@getSubservice');