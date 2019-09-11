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

Route::get('listar_interes_prestamo/{anio}/{mes}','CuentaSindicatoController@listar_interes_prestamo');

//-------------------------------------------------------------------------------------------------

//PRESTAMO-----------------------------------------------------

Route::post('guardar_prestamo','CsPrestamoAlejandroController@ingresar_prestamo');
Route::get('listar_p_salud_retornable/{anio}/{mes}','CsPrestamoAlejandroController@listar_p_salud_retornable');

Route::post('pagar_p_salud_retornable','CsPrestamoAlejandroController@pagar_p_salud_retornable');
Route::post('pago_abono','CsPrestamoAlejandroController@pago_abono');
Route::post('pagar_p_apuro_economico_retornable','CsPrestamoAlejandroController@pagar_p_apuro_economico_retornable');

Route::get('listar_abonos_por_prestamo/{id}/{tipo}','CsPrestamoAlejandroController@listar_abonos_por_prestamo_id');
Route::get('tabla_final_prestamo/{anio}/{mes}',	'CsPrestamoAlejandroController@tabla_final_prestamo');
Route::get('listar_prestamos_vigentes','CsPrestamoAlejandroController@listar_prestamos_vigentes');
Route::get('listar_prestamos_finalizados','CsPrestamoAlejandroController@listar_prestamos_finalizados');

Route::get('calcular_cuota_prestamo/{prestamo_id}','CsPrestamoAlejandroController@calcular_cuota_prestamo');
Route::get('calcular_abono/{prestamo_id}/{tipo_abono}','CsPrestamoAlejandroController@calcular_abono');


//----selects
Route::get('traer_regiones','geoController@traer_regiones');
Route::get('traer_provincia/{region}','geoController@traer_provincia');
Route::get('traer_comuna/{provincia}','geoController@traer_comuna');

//

//CUENTA SOCIOS-------------------------------------------------------------------------------

Route::post('ingresar_socio','SocioController@ingresar');
Route::get('listar_socios','SocioController@listar');
Route::get('filtrar_socios/{busca?}', 'SocioController@filtrar');
Route::post('editar_socios', 'SocioController@actualizar_socio');

Route::get('buscar_socio_por_rut/{rut}','SocioController@socio_por_rut');

Route::get('estado_de_socio_en_portal_beneficio/{id}','SocioController@estado_de_socio_en_portal_beneficio');

Route::post('guardar_datos_socio','SocioController@guardar_datos_socio');
Route::post('actualizar_datos_socio','SocioController@actualizar_datos_socio');
Route::get('traer_datos_socio/{socio_id}','SocioController@traer_datos_socio');

Route::post('guardar_datos_conyuge','SocioController@guardar_datos_conyuge');
Route::get('traer_datos_conyuge/{socio_id}','SocioController@traer_datos_conyuge');
Route::post('actualizar_datos_conyuge','SocioController@actualizar_datos_conyuge');

Route::post('guardar_datos_beneficiario','SocioController@guardar_datos_beneficiario');
Route::get('traer_datos_beneficiario/{socio_id}','SocioController@traer_datos_beneficiario');
Route::post('actualizar_datos_beneficiario','SocioController@actualizar_datos_beneficiario');


Route::post('guardar_datos_carga','SocioController@guardar_datos_carga');
Route::get('traer_datos_carga/{socio_id}','SocioController@traer_datos_carga');
Route::post('actualizar_datos_carga','SocioController@actualizar_datos_carga');


Route::post('guardar_datos_padres_suegros','SocioController@guardar_datos_padres_suegros');
Route::get('traer_datos_padres_suegros/{socio_id}','SocioController@traer_datos_padres_suegros');
Route::post('actualizar_datos_padres_suegros','SocioController@actualizar_datos_padres_suegros');

Route::post('subir_archivo_general_socio','SocioController@subir_archivo_general_socio');

//---------------------------------------------------------------------------------------------------



//CUENTA BIEN ESTAR----------------------------------------------------------------------------------

Route::post('cbe_guardar_inicio_mensual','InicioCierreMensualBienestarController@guardar_inicio_mensual');
Route::get('cbe_calcular_cm/{anio}/{mes}', 'InicioCierreMensualBienestarController@calcular_cierre_e_inicio_mensual');
Route::get('listar_inicio_y_cierre_mensual_cbe/{anio}','InicioCierreMensualBienestarController@listar_cierre_mensual_cbe');

Route::get('traer_monto_inicial_cbe/{anio}/{mes}','InicioCierreMensualBienestarController@traer_monto_inicial');
Route::get('calcular_caja_chica_anterior_cbe/{anio}/{mes}', 'CuentaBienestarController@existe_dinero_mes_anterior_caja_chica');
Route::get('calcular_caja_chica_anterior_cbe2/{anio}/{mes}','CuentaBienestarController@dinero_mes_anterior_caja_chica_2');

Route::post('cbe_insertar', 'CuentaBienestarController@insertar');
Route::get('cbe_listar/{anio}/{mes}', 'CuentaBienestarController@listar_cuenta');


