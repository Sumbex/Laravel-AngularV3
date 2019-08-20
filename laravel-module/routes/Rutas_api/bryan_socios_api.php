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
