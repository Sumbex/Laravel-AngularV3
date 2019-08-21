<?php

Route::get("documento_resumen_socio/{idSocio}","ArchivosSocioController@MostrarDocumentoGeneral");
Route::get("documento_registro_civil/{idSocio}","ArchivosSocioController@MostrarRegistroCivil");
Route::get("documento_certificado_nacimiento/{idCarga}","ArchivosSocioController@MostrarCertificadoNacimiento");