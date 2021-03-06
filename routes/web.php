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
Route::get('contacto',function(){
    return view('contacto');
});
Route::get('tvazteca',function(){
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin', 'HomeController@admin')->middleware('auth','checkadmin');

Route::resource('categorias', 'CategoryController');
Route::resource('reportes', 'Admin\ReporteController');
Route::resource('servicios', 'ServiceController');
Route::resource('sub-categorias', 'SubCategoryController',['except' => ['show']]);

// MODULES
Route::prefix('clientes')->group(base_path('routes/clientes.php'));
Route::prefix('sub-servicios')->group(base_path('routes/subServicios.php'));
Route::prefix('sub-categorias')->group(base_path('routes/subCategorias.php'));
Route::prefix('ordenes')->group(base_path('routes/ordenes.php'));
Route::prefix('tecnicos')->group(base_path('routes/tecnicos.php'));
Route::prefix('cupones')->group(base_path('routes/cupones.php'));
Route::prefix('pagos')->group(base_path('routes/pagos.php'));
Route::prefix('notificaciones-push')->group(base_path('routes/notificaciones-push.php'));
// AJAX REQUEST
Route::get('getSubcategory/{category_id}','SubCategoryController@getSubcategory');
Route::get('getSubservice/{service_id}','SubServiceController@getSubservice');
Route::get('getServices/{category_id}','ServiceController@getServicesByCategory');

//CHAT
Route::get('messenger','Chat\MessageController@messenger');
Route::get('/api/conversations/{type}', 'Chat\ConversationController@index');
Route::get('/api/messages', 'Chat\MessageController@index');
Route::post('/api/messages', 'Chat\MessageController@store');
Route::post('nueva-conversacion/{id}/{nombres}/{id_anuncio}','Chat\ConversationController@nueva_conversacion');

//Notifications
Route::get('notificaciones','HomeController@notificaciones');
Route::post('markasread','HomeController@markasread');
Route::get('testOrder', 'ApiRest\OrderController@testOrder');
Route::get('test','WebController@test');

Route::get('check','Admin\OrderController@check');
Route::get('terminos-y-condiciones','WebController@terminos');
Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

//Email confirmation
Route::get('email-confirmation/{code}','WebController@email_verified');
