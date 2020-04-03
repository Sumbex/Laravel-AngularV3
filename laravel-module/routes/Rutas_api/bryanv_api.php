<?php
Route::post('set_acuerdo', 'SecACuerdosController@setAcuerdo');
Route::get('get_acuerdos', 'SecACuerdosController@getAcuerdos');

//Rutas liquidaciones de sueldo
Route::post('setDatosLiquidacion', 'HistorialLiquidacionesController@setDatosLiquidacion');
Route::post('actualizarLiquidacion', 'HistorialLiquidacionesController@actualizarLiquidacion');
Route::get('getLiquidacionesPorId/{id}', 'HistorialLiquidacionesController@getLiquidacionesPorId');