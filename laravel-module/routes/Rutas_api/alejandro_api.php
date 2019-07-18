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
Route::get('calcular_caja_chica_anterior_cs/{anio}/{mes}', 'CuentaSindicatoController@existe_dinero_mes_anterior_caja_chica');
Route::get('calcular_caja_chica_anterior_cs2/{anio}/{mes}','CuentaSindicatoController@dinero_mes_anterior_caja_chica_2');

Route::post('actualizar_dato_cs','CuentaSindicatoController@actualizar_dato_cs');

Route::get('descargar_archivo@DescargarArchivo');
Route::get('listar_detalle_camping/{anio}/{mes}/{monto?}','CsDetalleCampingController@listar_detalle_camping');
Route::post('guardar_cierre_monto_detalle_camping_cs','CsDetalleCampingController@guardar_cierre_monto_detalle_camping_cs');
Route::get('traer_cierre_monto_detalle_camping_cs/{anio}/{mes}','CsDetalleCampingController@traer_cierre_monto_detalle_camping_cs');

//-------------------------------------------------------------------------------------------------

//CUENTA SOCIOS-------------------------------------------------------------------------------

Route::post('ingresar_socio','SocioController@ingresar');
Route::get('listar_socios','SocioController@listar');
Route::get('filtrar_socios/{busca?}', 'SocioController@filtrar');
Route::post('editar_socios', 'SocioController@actualizar_socio');

