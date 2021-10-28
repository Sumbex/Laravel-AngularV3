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
Route::post('login_rut', 'AuthController@login_rut');
Route::post('login_socios', 'PortalSocioController@LoginSocios');
/* Route::group(['middleware' => ['jwt.auth', 'cors', 'socio']], function () {
	require 'Rutas_api/bryan_socios_api.php';
}); */
/* require 'Rutas_api/bryan_socios_api.php'; */

//CUANDO SE AUTORIZA UN USUARIO
Route::group(['middleware' => ['jwt.auth', 'cors']], function () {

	//rutas con auth (loged)
	Route::group(['middleware' => ['administrador']], function () {
		Route::get('loged', function () {
			return "loged";
		});
		Route::get('auth/logout', 'AuthController@logout');

		Route::post('guardar_item_c_s', 'CuentaSindicatoController@guardar_item_cuenta_sindicato');
		Route::get('listar_c_s/{anio}/{mes}', 'CuentaSindicatoController@listar_cuenta_sindicato');
		Route::post('guardar_inicio_mensual', 'CierreMensualController@guardar_inicio_mensual');
		Route::get('calcular_cm/{anio}/{mes}', 'CierreMensualController@calcular_cierre_e_inicio_mensual');

		require 'Rutas_api/alejandro_api.php';
		require 'Rutas_api/bryan_api.php';
		require 'Rutas_api/david_api.php';
		require 'Rutas_api/bryanv_api.php';
	});
	//['middleware' => 'auth']['middleware' => 'socio']
	Route::group(['middleware' => ['socio']], function () {
		require 'Rutas_api/bryan_socios_api.php';
		require 'Rutas_api/bryanv_socios_api.php';
		require 'Rutas_api/david_socios_api.php';
		require 'Rutas_api/alejandro_socios_api.php';
	});
});

Route::post('documentacion_sindical/insert', 'DocumentacionSindicalController@insert');
Route::get('documentacion_sindical/list/{tipo}/{activo}', 'DocumentacionSindicalController@list');
Route::get('documentacion_sindical/tipos/list', 'DocumentacionSindicalController@tipo_documentacion_sindical');
Route::get('documentacion_sindical/suspender/{doc}','DocumentacionSindicalController@suspender');
Route::get('documentacion_sindical/activar/{doc}','DocumentacionSindicalController@activar');

Route::get('traer_socios_activos/{tipo}','SecTemasController@traerSociosActivos');
