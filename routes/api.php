<?php

use App\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiRest\ApiServiceController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::resource('categories', 'CategoryController');

//SERVICES
Route::post('oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
Route::get('sub-categories/{category}','ApiRest\ApiServiceController@getSubCategories');
Route::get('services/{subCategory}','ApiRest\ApiServiceController@getServices');
Route::post('orders/create','ApiRest\OrderController@create');

//Categories
Route::get('categories','ApiRest\ApiServiceController@getCategories');

//AUTH
Route::post('register','ApiRest\RegisterController@register');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    $user = $request->user();
    $info = new ApiServiceController();
    $final = $info->userInfo($user->id);
    return $final;
});
//DELEGATIONS
Route::get('delegations','ApiRest\DelegationController@index');

//FixerMan
Route::get('infoFixerman/{id}/{order_id}','ApiRest\FixerManController@infoFixerman');
Route::get('homeFixerMan/{id}','ApiRest\FixerManController@homeFixerMan');
Route::get('historyReviews/{id}','ApiRest\FixerManController@historyReviews');
Route::get('historyReviewsandOrders/{id}','ApiRest\FixerManController@historyReviewsandOrders');
Route::get('filterReviews/{user_id}/{filter}','ApiRest\FixerManController@filterReviews');
Route::post('registerFixerMan','ApiRest\FixerManController@register');
Route::post('updateUserField','ApiRest\FixerManController@updateUserField');
Route::post('saveSelectedOrder','ApiRest\FixerManController@saveSelectedOrder');
Route::post('aprobarSolicitudTecnico','ApiRest\FixerManController@aprobarSolicitudTecnico');
Route::post('eliminarSolicitudTecnico','ApiRest\FixerManController@eliminarSolicitudTecnico');
Route::post('terminarOrden','ApiRest\FixerManController@terminarOrden');
Route::post('qualifyService','ApiRest\FixerManController@qualifyService');

//Client
Route::get('historyOrders/{id}','ApiRest\ClientController@historyOrders');
Route::get('orderDetail/{id}/{order_id}','ApiRest\ClientController@orderDetail');

//Notifications
Route::get('notifications/{id}','ApiRest\NotificationsController@getNotifications');
Route::post('notifications/markAsRead/{id}','ApiRest\NotificationsController@markAsRead');
Route::post('notifications/deleteNotification/{id}','ApiRest\NotificationsController@deleteNotification');


//Chat
Route::get('conversationsRest/{id}', 'Chat\ConversationController@indexRest');
Route::get('messagesRest/{user_id}/{contact_id}', 'Chat\MessageController@indexRest');
Route::post('messagesRest', 'Chat\MessageController@storeRest');