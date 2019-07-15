<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Socios extends Model
{
    protected function filtrar($search)
    {
    	return DB::select("SELECT * from socios
						where lower(CONCAT(nombres,' ',a_paterno)) like  '%$search%'");
    }
}
