<?php 


Route::get('listar_anios','DatosBasicosController@listar_anios');
Route::get('anio_actual','DatosBasicosController@anio_actual');
Route::get('listar_meses','DatosBasicosController@listar_meses');
Route::get('mes_actual','DatosBasicosController@mes_actual');
Route::get('listar_definicion','DatosBasicosController@listar_definicion');
Route::get('listar_tipo_cuenta_sindicato','DatosBasicosController@listar_tipo_cuenta_sindicato');

Route::get('insertar','DatosBasicosController@insertar');

