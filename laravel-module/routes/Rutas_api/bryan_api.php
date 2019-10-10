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

//------------------------------------------->ruta socio
Route::post('/crear_usuario_socio', 'PortalSocioController@CrearUsuarioSocio');
Route::post('/borrar_usuario_socio', 'PortalSocioController@BorrarUsuarioSocio');


//------------------------------------------->accesos admin cambiar de lado despues
Route::post('/crear_usuario_admin', 'PortalSocioController@CrearUsuariosAdmin');
Route::post('/borrar_usuario_admin', 'PortalSocioController@BorrarUsuariosAdmin');

//------------------------------------------->cuenta bienestar
Route::post('/ingresar_caja_chica_bienestar', 'CajaChicaBienestarController@IngresarCajaChica');
Route::get('/traer_caja_chica_bienestar/{anio}/{mes}', 'CajaChicaBienestarController@TraerCajaChicaTotal');
Route::post('/modificar_caja_chica_bienestar', 'CajaChicaBienestarController@ModificarCajaChica');
//------------------------------------------->cuenta bienestar

//------------------------------------------->secretaria
Route::get('/traer_tipo_reunion_sec', 'SecReunionesController@TraerTipoReunion');
Route::post('/crear_reunion_sec', 'SecReunionesController@CrearReunion');
Route::get('/traer_reunion_activa_sec', 'SecReunionesController@TraerReunionActiva');
Route::post('/modificar_reunion_activa_sec', 'SecReunionesController@ModificarReunionActiva');
Route::post('/cancelar_reunion_activa_sec', 'SecReunionesController@CancelarReunionActiva');
//------------------------------------------->secretaria

/* Route::post('login_socios', 'PortalSocioController@LoginSocios'); */


//Route::get('/testeo', function() {
//   return "kkck";
//});
