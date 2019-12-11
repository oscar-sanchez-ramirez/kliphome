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
Route::post('registerFixerMan','ApiRest\FixerManController@register');
Route::get('homeFixerMan/{id}','ApiRest\FixerManController@homeFixerMan');