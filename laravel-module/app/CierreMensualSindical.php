<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CierreMensualSindical extends Model
{
    protected $table = "c_s_cierre_mensual";



    protected function listar_cierre_mensual_cs($anio, $mes, $cuenta)
    {
    	if ($cuenta == '1') { //cuenta sindicato
    		return DB::select('SELECT 
								cs_cm.id, 
							    a.descripcion as anio,
							    m.descripcion as mes,
							    cs_cm.inicio_mensual,
							    cs_cm.cierre_mensual
							from c_s_cierre_mensual as cs_cm
							inner join anio as a on a.id = cs_cm.anio_id
							inner join mes as m on m.id = cs_cm.mes_id
							where cs_cm.activo = ?
							and cs_cm.anio_id = ?
							and cs_cm.mes_id = ?', ['S', $anio, $mes ]);
    	}
    }
}
