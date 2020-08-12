<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
Route::post('orders/newDate/{id}','ApiRest\OrderController@newDate');
Route::post('orders/save_extra_info_for_order/{id}','ApiRest\OrderController@save_extra_info_for_order');
Route::get('orders/extra_info_for_order_detail/{id}','ApiRest\FixerManController@extra_info_for_order_detail');
Route::post('orders/save_gallery/{id}','ApiRest\OrderController@save_gallery');
Route::post('orders/suspendQuotation/{id}','ApiRest\OrderController@suspendQuotation');
Route::get('check_active_coupon','ApiRest\OrderController@check_active_coupon');

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
Route::post('verifyphone','ApiRest\RegisterController@verifyphone');
Route::post('check_social_account','ApiRest\RegisterController@check_social_account');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    $user = $request->user();
    $info = new ApiServiceController();

    $needle = "Mac";
    $haystack = $request->header('User-Agent');
    Log::notice($haystack);
    if (strpos($haystack, $needle) !== false){
        $needle = "iPhone";
    } else{
        $needle = "Android";
    }
    $final = $info->userInfo($user->id,$needle);
    return $final;
});
Route::get('getAccepted/{page}','ApiRest\ApiServiceController@getAccepted');
//DELEGATIONS
Route::get('delegations','ApiRest\DelegationController@index');

//FixerMan
Route::get('infoFixerman/{id}/{order_id}','ApiRest\FixerManController@infoFixerman');
Route::get('paymentsFixerman','ApiRest\FixerManController@paymentsFixerman');
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
Route::get('clientorderDetail/{order_id}','ApiRest\FixerManController@clientorderDetail');
Route::post('requirequotation','ApiRest\FixerManController@requirequotation');

//Client**
Route::get('historyOrders/{id}','ApiRest\ClientController@historyOrders');
Route::get('orderDetail/{order_id}','ApiRest\ClientController@orderDetail');
Route::post('addAddress','ApiRest\ClientController@addAddress');
Route::post('deleteAddress','ApiRest\ClientController@deleteAddress');
Route::post('confirmArrive','ApiRest\ClientController@confirmArrive');
Route::post('confirmArriveDate','ApiRest\ClientController@confirmArriveDate');
Route::get('list_payments','ApiRest\ClientController@list_payments');
Route::get('quotationDetail/{id}','ApiRest\ClientController@quotationDetail');

//Notifications**
Route::get('notifications/{id}','ApiRest\NotificationsController@getNotifications');
Route::post('notifications/markAsRead/{id}','ApiRest\NotificationsController@markAsRead');
Route::post('notifications/markAllAsRead','ApiRest\NotificationsController@markAllAsRead');
Route::post('notifications/deleteAllNotifications','ApiRest\NotificationsController@deleteAllNotifications');
Route::post('notifications/deleteNotification/{id}','ApiRest\NotificationsController@deleteNotification');


//Payments
Route::post('saveCustomer','ApiRest\PaymentController@saveCustomer');

//Chat**
Route::get('conversationsRest/{id}', 'ApiRest\ConversationController@indexRest');
Route::get('messagesRest/{user_id}/{contact_id}/{order_id}/{page}', 'ApiRest\MessageController@indexRest');
Route::post('messagesRest', 'ApiRest\MessageController@storeRest');
Route::post('new_conversation','ApiRest\ConversationController@new_conversation');
Route::post('markConversationAsRead','ApiRest\ConversationController@markConversationAsRead');

Route::post('webhook_oxxo','ApiController@webhook_oxxo');
Route::get('postal_code/{id}','ApiController@postal_code');


//Login con redes
Route::post('facebook','ApiRest\SocialController@facebook');
Route::post('google','ApiRest\SocialController@google');

//Conekta Payment
Route::get('conekta','ApiRest\SocialController@conekta');
