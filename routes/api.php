<?php
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
//Orders**
Route::post('orders/create','ApiRest\OrderController@create');
Route::post('orders/suspend','ApiRest\OrderController@suspend');
Route::post('orders/approve','ApiRest\OrderController@approve');
Route::post('orders/coupon','ApiRest\OrderController@coupon');

//Categories
Route::get('categories','ApiRest\ApiServiceController@getCategories');

//AUTH
Route::post('register','ApiRest\RegisterController@register');
Route::post('resetPassword','ApiRest\RegisterController@reset');
Route::post('validateCode','ApiRest\RegisterController@validateCode');
Route::post('updatePassword','ApiRest\RegisterController@updatePassword');
Route::post('updateAddress','ApiRest\RegisterController@updateAddress');
Route::post('newAddress','ApiRest\RegisterController@newAddress');
Route::post('verifyemail','ApiRest\RegisterController@verifyemail');
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
Route::get('fixerManorderDetail/{order_id}','ApiRest\FixerManController@fixerManorderDetail');

//Client**
Route::get('historyOrders/{id}','ApiRest\ClientController@historyOrders');
Route::get('orderDetail{order_id}','ApiRest\ClientController@orderDetail');
Route::post('addAddress','ApiRest\ClientController@addAddress');
Route::post('deleteAddress','ApiRest\ClientController@deleteAddress');
Route::post('confirmArrive','ApiRest\ClientController@confirmArrive');

//Notifications**
Route::get('notifications/{id}','ApiRest\NotificationsController@getNotifications');
Route::post('notifications/markAsRead/{id}','ApiRest\NotificationsController@markAsRead');
Route::post('notifications/deleteNotification/{id}','ApiRest\NotificationsController@deleteNotification');


//Chat**
Route::get('conversationsRest/{id}', 'ApiRest\ConversationController@indexRest');
Route::get('messagesRest/{user_id}/{contact_id}/{order_id}/{page}', 'ApiRest\MessageController@indexRest');
Route::post('messagesRest', 'ApiRest\MessageController@storeRest');
Route::post('new_conversation','ApiRest\ConversationController@new_conversation');

Route::post('webhook_oxxo','ApiController@webhook_oxxo');
