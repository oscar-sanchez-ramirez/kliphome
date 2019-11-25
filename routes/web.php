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

use App\User;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin', function () {
    return view('admin.admin');
})->middleware('auth');

Route::resource('categorias', 'CategoryController');
Route::resource('servicios', 'ServiceController',['except' => ['show']]);
Route::resource('sub-categorias', 'SubCategoryController',['except' => ['show']]);

Route::post('sub-categorias/nuevo','SubCategoryController@nuevo');
Route::post('sub-categorias/eliminar','SubCategoryController@eliminar');
Route::post('sub-categorias/actualizar','SubCategoryController@actualizar');
Route::get('getSubcategory/{category_id}','SubCategoryController@getSubcategory');

Route::post('sub-servicios/nuevo','SubServiceController@nuevo');
Route::post('sub-servicios/eliminar','SubServiceController@eliminar');
Route::post('sub-servicios/actualizar','SubServiceController@actualizar');
Route::get('getSubservice/{service_id}','SubServiceController@getSubservice');

Route::get('checkUser',function(){
    return User::where('id',13)->with('children')->get();
});