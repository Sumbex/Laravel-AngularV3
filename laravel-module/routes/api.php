<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'AuthController@login');

//CUANDO SE AUTORIZA UN USUARIO
Route::group(['middleware' => 'jwt.auth'], function(){

	//rutas con auth (loged)
	Route::get('loged', function(){ return "loged"; });
	Route::get('auth/logout','AuthController@logout');

});