<?php
Route::post('set_acuerdo', 'SecACuerdosController@setAcuerdo');
Route::get('get_acuerdos', 'SecACuerdosController@getAcuerdos');

//Rutas generacion liquidaciones de sueldo
Route::get('getConfiguracionHaberesPorIdEmpleado/{id}', 'HistorialLiquidacionesController@getConfiguracionHaberesPorIdEmpleado');
Route::get('getConfiguracionDescuentosPorIdEmpleado/{id}', 'HistorialLiquidacionesController@getConfiguracionDescuentosPorIdEmpleado');

// RUTA PARA GUARDAR EL REGISTRO DE LIQUIDACIONES
Route::post('setHistorialLiquidacion', 'HistorialLiquidacionesController@setHistorialLiquidacion');
Route::get('getLiquidacionesGeneradasPorIdEmpleado/{id}', 'HistorialLiquidacionesController@getLiquidacionesGeneradasPorIdEmpleado');

//Rutas historial liquidaciones de sueldo
Route::post('setDatosLiquidacion', 'HistorialLiquidacionesController@setDatosLiquidacion');
Route::post('actualizarLiquidacion', 'HistorialLiquidacionesController@actualizarLiquidacion');
Route::get('getLiquidacionesPorId/{id}', 'HistorialLiquidacionesController@getLiquidacionesPorId');

// Rutas para traer las liquidaciones generadas
Route::get('getHaberesPorIdLiquidacion/{id}', 'HistorialLiquidacionesController@getHaberesPorIdLiquidacion');
Route::get('getDescuentosPorIdLiquidacion/{id}', 'HistorialLiquidacionesController@getDescuentosPorIdLiquidacion');