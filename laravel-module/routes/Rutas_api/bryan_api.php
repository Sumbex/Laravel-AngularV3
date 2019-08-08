<?php

//------------------------------------------>rutas caja chica

Route::post('/ingresar_caja_chica', 'CajaChicaController@IngresarCajaChica');
Route::post('/modificar_caja_chica', 'CajaChicaController@ModificarDatos');
Route::get('/existe_caja_chica/{anio}/{mes}', 'CajaChicaController@ExisteCajaChica');
Route::get('/traer_caja_chica/{anio}/{mes}', 'CajaChicaController@TraerCajaChica');
Route::get('/totales_caja_chica/{anio}/{mes}', 'CajaChicaController@TotalIngEgre');

//------------------------------------------>rutas prestamo 

Route::get('/traer_tipo_prestamo', 'PrestamosController@TraerTipoPrestamos');
Route::get('/traer_socio/{rut}', 'PrestamosController@TraerSocio');
Route::post('/ingresar_prestamo', 'PrestamosController@IngresarPrestamo');
Route::get('/traer_prestamos/{anio}/{mes}', 'PrestamosController@TraerPrestamos');
Route::get('/traer_prestamos_historicos/{anio}/{mes}', 'PrestamosController@TraerPrestamosHistorico');
Route::get('/traer_tipo_abono', 'PrestamosController@TraerTipoAbonos');
Route::post('/ingresar_pago_prestamo', 'PrestamosController@PagoPrestamos');
Route::post('/ingresar_pago_abono', 'PrestamosController@PagoAbonos');

//------------------------------------------>rutas portal socio

Route::post('/modificar_datos_socio', 'PortalSocioController@ModificarDatosSocios');
Route::get('/socio_logeado', 'PortalSocioController@SocioLogeado');

/* Route::post('login_socios', 'PortalSocioController@LoginSocios'); */


//Route::get('/testeo', function() {
//   return "kkck";
//});
