<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class geoController extends Controller
{
    public function traer_regiones()
    {
    	return DB::table('regiones');
    }
    public function traer_provincia($region_id)
    {
    	return DB::table('provincias')->where(['activo'=>'S','region_id'=>$region_id])->get();
    }
    public function traer_comuna($provincia_id)
    {
    	return  DB::table('comunas')->where(['activo'=>'S','provincia_id'=>$provincia_id])->get();
    }
}
