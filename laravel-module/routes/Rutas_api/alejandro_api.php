<?php 


//CUENTA SINDICATO--------------------------------------------------------------------------------

Route::get('listar_anios','DatosBasicosController@listar_anios');
Route::get('anio_actual','DatosBasicosController@anio_actual');
Route::get('listar_meses','DatosBasicosController@listar_meses');
Route::get('mes_actual','DatosBasicosController@mes_actual');
Route::get('listar_definicion','DatosBasicosController@listar_definicion');
Route::get('listar_tipo_cuenta_sindicato','DatosBasicosController@listar_tipo_cuenta_sindicato');

Route::get('listar_inicio_y_cierre_mensual_cs/{anio}','CierreMensualController@listar_cierre_mensual_cs');
Route::get('traer_monto_inicial_cs/{anio}/{mes}','CierreMensualController@traer_monto_inicial');

Route::get('insertar','DatosBasicosController@insertar');
Route::post('confirmar_usuario','DatosBasicosController@confirmar_usuario');
Route::post('cambiar_password', 'DatosBasicosController@cambiar_password');

Route::post('insertar_cs_prestamo', 'PrestamosController@insertar_cs');
Route::get('listar_cs_prestamo/{anio}/{mes}', 'PrestamosController@listar_cs_prestamo');

Route::get('usuario_logeado','DatosBasicosController@usuario_logeado');

//-------------------------------------------------------------------------------------------------

//CUENTA SOCIOS-------------------------------------------------------------------------------

Route::post('ingresar_socio','SocioController@ingresar');
