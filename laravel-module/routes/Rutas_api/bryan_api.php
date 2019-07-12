<?php

Route::post('/ingresar_caja_chica', 'CajaChicaController@IngresarCajaChica');
Route::post('/modificar_caja_chica', 'CajaChicaController@ModificarDatos');
Route::get('/existe_caja_chica/{anio}/{mes}', 'CajaChicaController@ExisteCajaChica');
Route::get('/traer_caja_chica/{anio}/{mes}', 'CajaChicaController@TraerCajaChica');
Route::get('/totales_caja_chica/{anio}/{mes}', 'CajaChicaController@TotalIngEgre');


//Route::get('/testeo', function() {
//   return "kkck";
//});
