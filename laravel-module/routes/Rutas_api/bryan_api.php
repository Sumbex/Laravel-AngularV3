<?php 

Route::post('/ingresar_caja_chica','CajaChicaController@IngresarCajaChica');
Route::get('/existe_caja_chica/{anio}/{mes}','CajaChicaController@ExisteCajaChica');
Route::get('/traer_caja_chica/{anio}/{mes}','CajaChicaController@TraerCajaChica');
//Route::get('/testeo', function() {
//   return "kkck";
//});