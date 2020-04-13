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
Route::post('/terminar_reunion_activa_sec', 'SecReunionesController@CerrarReunionActiva');
Route::post('/archivar_reunion_sec', 'SecReunionesController@ArchivarReunion');
Route::get('/traer_reunion_cf_sec', 'SecReunionesController@TraerReunionCerradaFinalizada');
Route::get('/traer_socio_por_rut_sec/{rut}/{reunion}', 'SecReunionesController@TraerSocioPorRut');
Route::post('/marcar_asistencia_socio_sec', 'SecReunionesController@MarcarAsitenciaSocioReunion');
Route::get('/traer_socios_presentes_sec/{reunion}', 'SecReunionesController@TraerSociosPresentes');
Route::get('/traer_lista_reunion_sec/{reunion}', 'SecReunionesController@TraerListaReunion');
Route::get('/traer_justificacion_socio_sec/{reunion}/{socio}', 'SecReunionesController@TraerJustificacionSocio');
Route::get('/traer_historial_reuniones_sec', 'SecReunionesController@TraerHistorialReuniones');
Route::get('/filtrar_socios_lista_reuniones_sec/{reunion}/{socio?}', 'SecReunionesController@FiltrarSocio');

Route::post('/crear_noticia_sec', 'SecLeyesController@IngresarNoticia');

//------------------------------------------->secretaria

//------------------------------------------->notas
Route::post('/ingresar_notas', 'NotasCuentasController@IngresarNota');
Route::post('/actualizar_nota', 'NotasCuentasController@ActualizarNota');
Route::get('/traer_notas/{anio}/{mes}', 'NotasCuentasController@TraerNotas');
Route::get('/traer_datos_archivador', 'NotasCuentasController@DatosBasicos');

//------------------------------------------->notas

//------------------------------------------->archivador
Route::post('/ingresar_archivos', 'ArchivadorController@IngresarArchivo');
Route::post('/actualizar_archivo', 'ArchivadorController@ActualizarArchivo');
Route::get('/traer_archivos/{anio}/{mes}', 'ArchivadorController@TraerArchivos');
Route::get('/traer_tipos_archivador', 'ArchivadorController@TraerTipos');
//------------------------------------------->archivador

//------------------------------------------->Temas/Votos
Route::post('/ingresar_tema', 'SecTemasController@IngresarTema');
Route::post('/actualizar_tema', 'SecTemasController@ActualizaTema');
Route::post('/cerrar_tema', 'SecTemasController@CerrarTema');
Route::post('/cancelar_tema', 'SecTemasController@CancelarTema');
Route::get('/traer_temas/{anio}/{mes}/{tipo}', 'SecTemasController@TraerTemas');
Route::get('/traer_temas_activos', 'SecTemasController@TraerTemasActivos');
Route::get('/traer_tipos_temas', 'SecTemasController@TraerTiposTemas');
Route::get('/traer_votos_tema/{tema}', 'SecTemasController@TraerVotos');
//------------------------------------------->Temas/Votos

/* Route::post('login_socios', 'PortalSocioController@LoginSocios'); */


//Route::get('/testeo', function() {
//   return "kkck";
//});
