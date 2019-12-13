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
})->middleware('auth','checkadmin');

Route::resource('categorias', 'CategoryController');
Route::resource('servicios', 'ServiceController');
Route::resource('sub-categorias', 'SubCategoryController',['except' => ['show']]);

// MODULES
Route::prefix('clientes')->group(base_path('routes/clientes.php'));
Route::prefix('sub-servicios')->group(base_path('routes/subServicios.php'));
Route::prefix('sub-categorias')->group(base_path('routes/subCategorias.php'));
Route::prefix('ordenes')->group(base_path('routes/ordenes.php'));
Route::prefix('tecnicos')->group(base_path('routes/tecnicos.php'));

// AJAX REQUEST
Route::get('getSubcategory/{category_id}','SubCategoryController@getSubcategory');
Route::get('getSubservice/{service_id}','SubServiceController@getSubservice');
Route::get('getServices/{category_id}','ServiceController@getServicesByCategory');


Route::get('test', 'Admin\NotificationsProvider@test');
Route::get('testMatch','Admin\NotificationsProvider@testMatch');