<?php

use App\Address;
use Illuminate\Http\Request;

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

    if($user->type == "AppFixerMan"){
        $delegation = DB::table('selected_delegations as s')->join('delegations as d','s.delegation_id','d.id')->select('s.id','d.id as delegation_id','d.title')->where('s.user_id',$user->id)->get();
        $categories = DB::table('selected_categories as s')->join('categories as c','c.id','s.category_id')->select('s.id','c.id as category_id','c.title')->where('s.user_id',$user->id)->get();
        return response()->json([
            'user' => $user,
            'delegations' => $delegation,
            'categories' => $categories
        ]);
    }elseif($user->type == "AppUser"){
        $address = Address::where('user_id',$user->id)->get();
        return array($user,$address);
    }
});
//DELEGATIONS
Route::get('delegations','ApiRest\DelegationController@index');

//FixerMan
Route::post('registerFixerMan','ApiRest\FixerManController@register');
Route::get('homeFixerMan/{id}','ApiRest\FixerManController@homeFixerMan');