<?php
//------------------------------------------>rutas portal socio
Route::post('/modificar_datos_socio', 'PortalSocioController@ModificarDatosSocios');
Route::get('/socio_logeado', 'PortalSocioController@SocioLogeado');
Route::get('/traer_datos_basicos_socio', 'PortalSocioController@TraerDatosBasicosSocios');
Route::post('/ingresar_datos_basicos_socio', 'PortalSocioController@IngresarDatosBasicosSocio');
Route::post('/modificar_datos_basicos_socio', 'PortalSocioController@ModificarDatosBasicosSocio');
Route::get('/traer_datos_conyuge_socio', 'PortalSocioController@TraerDatosConyuge');
Route::post('/ingresar_datos_conyuge_socio', 'PortalSocioController@IngresarConyugeSocio');
Route::post('/ingresar_datos_beneficiarios_socio', 'PortalSocioController@IngresarBeneficiariosSocio');
Route::get('/traer_datos_beneficiarios_socio', 'PortalSocioController@TraerDatosBeneficiariosSocios');
Route::post('/cambiar_contrasena_socio', 'PortalSocioController@CambiarContrasena');
Route::post('/ingresar_datos_padres_suegros_socio', 'PortalSocioController@IngresarDatosPadresSuegrosSocio');
Route::get('/traer_datos_padres_suegros_socio', 'PortalSocioController@TraerDatosPadresSuegrosSocio');
Route::post('/ingresar_datos_cargas_socio', 'PortalSocioController@IngresarDatosCargasSocio');
Route::get('/traer_datos_cargas_socio', 'PortalSocioController@TraerDatosCargasSocio');
Route::get('/traer_archivo_resumen_socio', 'PortalSocioController@TraerArchivoResumen');
//------------------------------------------>rutas portal socio

//------------------------------------------>rutas datos basicos
Route::get('/traer_anios', 'PortalSocioController@TraerAnios');
Route::get('/traer_anio_actual', 'PortalSocioController@AnioActual');
Route::get('/traer_meses', 'PortalSocioController@TraerMeses');
Route::get('/traer_mes_actual', 'PortalSocioController@MesActual');
//------------------------------------------>rutas datos basicos

//------------------------------------------>rutas portal socio cs
Route::get('/traer_cuenta_sindical_socio/{anio}/{mes}', 'PortalSocioController@TraerCuentaSindical');
Route::get('/traer_caja_chica_socio/{anio}/{mes}', 'PortalSocioController@TraerCajaChica');
Route::get('/traer_camping_socio/{anio}/{mes}', 'PortalSocioController@TraerCamping');
//------------------------------------------>rutas portal socio cs

//------------------------------------------>rutas portal socio cb
Route::get('/traer_cuenta_bienestar_socio/{anio}/{mes}', 'PortalSocioController@TraerCuentaBienestar');
Route::get('/traer_caja_chica_bienestar_socio/{anio}/{mes}', 'PortalSocioController@TraerCajaChicaBienestar');
//------------------------------------------>rutas portal socio cb

//------------------------------------------>rutas portal socio cc
Route::get('/traer_cuenta_consorcio_socio/{anio}', 'PortalSocioController@TraerCuentaConsorcioAnual');
//------------------------------------------>rutas portal socio cc

//------------------------------------------>rutas portal socio mis beneficios
Route::get('/traer_prestamos_socio', 'PortalSocioController@TraerPrestamos');
Route::get('/traer_pagos_prestamos_socio/{id}/{tipo}', 'PortalSocioController@TraerPagosPrestamos');
Route::get('/traer_abonos_prestamos_socio/{id}/{tipo}', 'PortalSocioController@TraerPagosAbonos');
Route::get('/traer_nacimientos_socio', 'PortalSocioController@TraerNacimientos');
Route::get('/traer_fallecimientos_socio', 'PortalSocioController@TraerFallecimientos');
Route::get('/traer_gastos_medicos_socio', 'PortalSocioController@TraerGastosMedicos');
Route::get('/traer_mis_ahorros_socio/{anio}', 'PortalSocioController@TraerMisAhorros');
//------------------------------------------>rutas portal socio mis beneficios

//------reunion 
Route::post('/ingresar_justificacion_socio_sec', 'PortalSocioController@JustificarSocio');
//------reunion 

//------------------------------------------------FUNCION TEST------------------------------------------------//
Route::get('/funcion_test/{anio}/{mes}', 'PortalSocioController@FuncionTest');
Route::get('/funcion_test2', 'PortalSocioController@FuncionTest2');

//------------------------------------------------FUNCION TEST------------------------------------------------//
