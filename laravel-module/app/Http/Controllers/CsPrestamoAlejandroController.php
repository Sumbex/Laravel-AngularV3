<?php

namespace App\Http\Controllers;

use App\AbonoSalud;
use App\CsPrestamo;
use App\Socios;
use App\p_apuro_economico_retornable;
use App\p_salud_retornable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CsPrestamoAlejandroController extends Controller
{
    public function ingresar_prestamo(Request $r)
    {
    	try{
	    	$tipo_prestamo = (string)$r->select_id; // tipo de prestamo;

	    	$valida_pdf = $this->validar_pdf($r);
	    	if($valida_pdf['estado'] == 'failed_v'){

	    		return ['estado'=>'failed', 'mensaje'=>'Error con el documento adjunto'];
	    	}


	    	switch ($tipo_prestamo) {

	    		case '1': // PRESTAMO SALUD RETORNABLE

	    			return $this->insertar_p_salud_retornable($r);
	    		
	    		break;

	    		case '2': // PRESTAMO APORTE ECONOMICO RETORNABLE
	    			
	    			return $this->insertar_p_apuro_economico_retornable($r);
	    		
	    		break;

	    		case '3': // PRESTAMO APORTE ECONOMICO NO RETORNABLR
	    			
	    			return $this->insertar_p_aporte_economico_nr($r);
	    		
	    		break;
	    		
	    		default:
	    			# code...
	    			break;
	    	}
	    }catch(QueryException $e){
			return[
				'estado'  => 'failed', 
				'mensaje' => 'QEx: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos'
			];
		}
		catch(\Exception $e){
			return[
				'estado'  => 'failed', 
				'mensaje' => 'Ex: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos'
			];
		}
    }
    public function listar_p_salud_retornable($anio, $mes)
    {
    	$salud = p_salud_retornable::listar($mes, $anio);
    	$apuro = p_apuro_economico_retornable::listar($mes, $anio);
    	$nr = p_apuro_economico_retornable::listar_nr($mes, $anio);

    	return[
    	  'salud' => $salud,
    	  'apuro' => $apuro,
    	  'aporte'=> $nr
    	];
    }

    function insertar_p_salud_retornable($r)
    {
    	$con_abono = $r->checkAbono; // true o false


    	$s = $this->socio($r->socio_id); // recuperar nombre del socio;
    	$f = $this->div_fecha($r->fecha); // dividir fecha por dia, mes, anio;
    	$anio = $this->anio_tipo_id($f['anio']);


    	$guardarArchivo = $this->guardarArchivo($r->archivo_documento, 'ArchivosPrestamo/');

        if ($guardarArchivo['estado'] == "success") {
                 
                    $prestamo = new CsPrestamo;
			    	$prestamo->descripcion_prestamo = 'Prestamo de salud retornable para '. $s;
			    	$prestamo->transferencia_bancaria = $r->numero_documento;
			    	$prestamo->archivo = 'storage/' . $guardarArchivo['archivo'];
			    	$prestamo->tipo_prestamo = $r->select_id;//tipo de prestamo
			    	$prestamo->definicion = '2';// sale dinero (egreso)
			    	$prestamo->egreso = $r->monto_total;
			    	$prestamo->anio_id = $anio->id;
			    	$prestamo->mes_id = $f['mes'];
			    	$prestamo->dia = $f['dia'];
			    	$prestamo->user_id = Auth::user()->id;
			    	$prestamo->socio_id = $r->socio_id;
			    	$prestamo->cuotas = $r->cuotas;
			    	$prestamo->estado_prestamo = 'vigente';
			    	$prestamo->estado_abono = ($con_abono == "true")? 'vigente' : 'sin abonos';
			    	$prestamo->activo = 'S';

			    	if ($prestamo->save()) {

			    		$monto_dia = !empty($r->monto_dia) ? $r->monto_dia : 0;
			    		$monto_tri = !empty($r->monto_tri) ? $r->monto_tri : 0;
			    		$monto_con = !empty($r->monto_con) ? $r->monto_con : 0; 
			    		$restante = $prestamo->egreso - $monto_dia - $monto_tri - $monto_con;

			    		$primer_psr = new p_salud_retornable; // al insertar prestamo se ingresa un primer detalle de egreso
			    		//$mas_psr new p_salud_retornable; // en caso de que existan muchas cuotas en prestamo;

			    		$primer_psr->prestamo_id = $prestamo->id;
			    		$primer_psr->monto_restante = $restante; //
			    		$primer_psr->numero_cuota = 0;
			    		$primer_psr->cuotas = $prestamo->cuotas;
			    		$primer_psr->abono = ($con_abono == "true")? 'S' : 'N';
			    		$primer_psr->monto_dia_sueldo = $monto_dia;
			    		$primer_psr->monto_trimestral = $monto_tri;
			    		$primer_psr->monto_termino_conflicto = $monto_con; 
			    		$primer_psr->ingreso = 0;
			    		$primer_psr->egreso =  $prestamo->egreso /*0*/;
			    		$primer_psr->definicion = '2'; // este detalle esta recien saliento como el prestamo;
			    		$primer_psr->estado_prestamo = 'vigente';
			    		$primer_psr->estado_abono = 'vigente';
			    		$primer_psr->anio_id = $anio->id;
			    		$primer_psr->mes_id = $f['mes'];
			    		$primer_psr->dia = $f['dia'];
			    		$primer_psr->user_id = $prestamo->user_id;

			    		if ($primer_psr->save()) { // si se ingresa el prestamo en salud retornable, puede insertar + items de cuotas
			    				
			    				return ['estado'=>'success', 'mensaje'=> 'prestamo realizado'];

			    		}

			    		return ['estado'=>'failed', 'mensaje'=> 'prestamo rechazado'];
			    	}
			    	return ['estado'=>'failed','mensaje'=>'error al hacer prestamo'];
        } else {
            return $guardarArchivo;
        }

	}
	function insertar_p_apuro_economico_retornable($r)
	{
		$porcentaje = 10;// % //
		$s = $this->socio($r->socio_id); // recuperar nombre del socio;
    	$f = $this->div_fecha($r->fecha); // dividir fecha por dia, mes, anio;
    	$anio = $this->anio_tipo_id($f['anio']);

    	$guardarArchivo = $this->guardarArchivo($r->archivo_documento, 'ArchivosPrestamo/');

        if ($guardarArchivo['estado'] == "success") {

		    	$prestamo = new CsPrestamo;
		    	$prestamo->descripcion_prestamo = 'Prestamo apuro económico retornable para '. $s;
		    	$prestamo->transferencia_bancaria = $r->numero_documento;
		    	$prestamo->archivo = 'storage/' . $guardarArchivo['archivo'];
		    	$prestamo->tipo_prestamo = $r->select_id;//tipo de prestamo
		    	$prestamo->definicion = '2';// sale dinero (egreso)
		    	$prestamo->egreso = $r->monto_total;
		    	$prestamo->anio_id = $anio->id;
		    	$prestamo->mes_id = $f['mes'];
		    	$prestamo->dia = $f['dia'];
		    	$prestamo->user_id = Auth::user()->id;
		    	$prestamo->socio_id = $r->socio_id;
		    	$prestamo->cuotas = $r->cuotas;
		    	$prestamo->estado_prestamo = 'vigente';
		    	$prestamo->activo = 'S';

		    	if ($prestamo->save()) {
		    		$paer = new p_apuro_economico_retornable;
		    		$paer->prestamo_id = $prestamo->id;
		    		$paer->numero_cuota = 0;
		    		$paer->cuotas = $prestamo->cuotas;
		    		$paer->interes_total = ($prestamo->egreso / $porcentaje);
		    		$paer->interes_mensual = 0;
		    		$paer->definicion = '2';// ested etalle sale de cuenta sindical
		    		$paer->ingreso = 0;
		    		$paer->egreso = $prestamo->egreso; 
		    		$paer->monto_restante = $prestamo->egreso;
		    		$paer->anio_id = $anio->id;
		    		$paer->mes_id = $f['mes'];
		    		$paer->dia = $f['dia'];
		    		$paer->user_id = Auth::user()->id;
		    		$paer->activo = 'S';
		    		$paer->estado_cuota = 'vigente';

		    		if ($paer->save()) {
		    			return ['estado'=>'success', 'mensaje'=> 'prestamo realizado'];
		    		}
		    		return ['estado'=>'failed', 'mensaje'=> 'Error al realizar prestamo'];

		    	}
		    	return ['estado'=>'failed', 'mensaje'=> 'Error al realizar prestamo'];
		}else{
			return $guardarArchivo;
		}
	}

	function insertar_p_aporte_economico_nr($r)
	{
		$s = $this->socio($r->socio_id); // recuperar nombre del socio;
    	$f = $this->div_fecha($r->fecha); // dividir fecha por dia, mes, anio;
    	$anio = $this->anio_tipo_id($f['anio']);

    	$guardarArchivo = $this->guardarArchivo($r->archivo_documento, 'ArchivosPrestamo/');

        if ($guardarArchivo['estado'] == "success") {

		    	$prestamo = new CsPrestamo;
		    	$prestamo->descripcion_prestamo = 'Prestamo aporte económico para '. $s;
		    	$prestamo->transferencia_bancaria = $r->numero_documento;
		    	$prestamo->archivo = 'storage/' . $guardarArchivo['archivo'];
		    	$prestamo->tipo_prestamo = $r->select_id;//tipo de prestamo
		    	$prestamo->definicion = '2';// sale dinero (egreso)
		    	$prestamo->egreso = $r->monto_total;
		    	$prestamo->anio_id = $anio->id;
		    	$prestamo->mes_id = $f['mes'];
		    	$prestamo->dia = $f['dia'];
		    	$prestamo->user_id = Auth::user()->id;
		    	$prestamo->socio_id = $r->socio_id;
		    	$prestamo->cuotas = $r->cuotas;
		    	$prestamo->estado_prestamo = 'egresado';
		    	$prestamo->activo = 'S';

		    	if ($prestamo->save()) {
		    		return [ 'estado'=>'success', 'mensaje'=>'Prestamo realizado' ];
		    	}
		    	return ['estado'=>'failed', 'mensaje'=> 'Error al realizar prestamo'];
		}else{
			return $guardarArchivo;
		}
	}

    public function pagar_p_salud_retornable(Request $r)
    {
    	$id = $r->p_salud_retornable_id;


    	$item = p_salud_retornable::find($id);
    	$ultimo_item = p_salud_retornable::where(['prestamo_id'=>$item->prestamo_id])->latest()->first();

    	//si el prestamo (item detalle) esta vigente y su trayecto de cuota es menor al total de cuotas
    	if (($ultimo_item->estado_prestamo == 'vigente' || $ultimo_item->estado_prestamo == 'pagando' ) && $ultimo_item->numero_cuota < $ultimo_item->cuotas) {

    		$monto_restante = p_salud_retornable::where(['prestamo_id'=>$ultimo_item->prestamo_id])->min('monto_restante'); 


    		if($monto_restante == 0){
    			return ['estado'=>'failed','mensaje'=>'EL monto restante esta en monto 0, posiblemente el prestamo ya esté pagado'];
    		}

    		if($r->monto > $monto_restante){
    			return ['estado'=>'failed','mensaje'=>'EL monto ingresado es muy grande para el restante del prestamo ('.$monto_restante.')'];
    		}
    		

    		$f = $this->div_fecha($r->fecha); // dividir fecha por dia, mes, anio;
    		$anio = $this->anio_tipo_id($f['anio']);

    		$psr = new p_salud_retornable;

    		$psr->prestamo_id = $ultimo_item->prestamo_id;
    		$psr->monto_restante = $ultimo_item->monto_restante - $r->monto; //menos lo que paga el socio
    		$psr->numero_cuota = $ultimo_item->numero_cuota + 1; //sumamos la cuota que corresponde
    		$psr->cuotas = $ultimo_item->cuotas;
    		$psr->abono = $ultimo_item->abono;
    		$psr->monto_dia_sueldo = $ultimo_item->monto_dia_sueldo;
    		$psr->monto_trimestral = $ultimo_item->monto_trimestral;
    		$psr->monto_termino_conflicto = $ultimo_item->monto_termino_conflicto;
    		$psr->ingreso = $r->monto;
    		$psr->definicion = '1';
    		$psr->estado_prestamo = (($ultimo_item->monto_restante - $r->monto) == 0)?'pagado' : 'pagando';
    		$psr->estado_abono = $ultimo_item->estado_abono;
    		$psr->anio_id = $anio->id;
    		$psr->mes_id = $f['mes'];
    		$psr->dia = $f['dia'];
    		$psr->user_id = Auth::user()->id;
    		if ($psr->save()) {

    			$monto_restante = p_salud_retornable::where(['prestamo_id'=>$psr->prestamo_id])->min('monto_restante'); 
	    		if($monto_restante == 0){

	    					$prestamo = CsPrestamo::find($psr->prestamo_id);
	    					$prestamo->estado_prestamo = 'pagado';
	    					$prestamo->save();

							return ['estado'=>'failed','mensaje'=>'Pago realizado, el monto restante ya esta en monto 0, se concidera la finalización del prestamo'];
	    		}

    			$prestamo = CsPrestamo::where(['activo'=>'S','id'=>$psr->prestamo_id])->first();
    			$prestamo->estado_prestamo = 'pagando';
    			$prestamo->save();

    			return ['estado'=>'success','mensaje'=>'Pago realizado correctamente!!'];
    		}
    		return ['estado'=>'failed','mensaje'=>'Error al pagar prestamo!!'];

    		
    	}else{
    		return ['estado'=>'failed','mensaje'=>'Puede que el prestamo ya esté finalizado'];
    	}
    }

    public function pago_abono(Request $r)
    {
    	$psr = p_salud_retornable::find($r->p_salud_retornable_id); //item prestamo salud retornable;
    	$monto_versus = 0; //variable total de un trimestral, dia sueldo o termino conflicto en item p_s_r
    	$monto_abono = 0;

    	$f = $this->div_fecha($r->fecha); // dividir fecha por dia, mes, anio;
    	$anio = $this->anio_tipo_id($f['anio']);

    
    	switch ($r->abono) {
    		case '1':
    			$monto_versus = $psr->monto_dia_sueldo;
    			$monto_abono = $psr->monto_dia_sueldo;

    		break;

    		case '2':
    			$monto_versus = $psr->monto_termino_conflicto;
    			$monto_abono = $psr->monto_termino_conflicto;
    		break;

    		case '3':
    			$monto_versus = $psr->monto_trimestral;
    			$monto_abono = $psr->monto_trimestral;
    	    break;
    		
    		default:
    			# code...
    			break;
    	}

    	$abono_min = AbonoSalud::where([
    						'prestamo_id' => $psr->prestamo_id,
    						'abono_tipo' => $r->abono,
    						'activo' => 'S'
    					])->min('restante_abono');

    	$abono_total = AbonoSalud::where([
    						'prestamo_id' => $psr->prestamo_id,
    						'abono_tipo' => $r->abono,
    						'activo' => 'S'
    					])->sum('monto');


    	//dd($abono_total." == ".$monto_abono);
    	if ($abono_total == $monto_abono) { // si el total del abono de este tipo es igual al abono solicitado
    		return ['estado'=>'failed','mensaje'=>'No es posible pagar abono, su monto esta en 0'];
    	}
    	if(!empty($abono_min)){
    		$monto_versus = $abono_min; //aqui pasa cuando pille un tipo de abono y sea el minimo que exista de su tipo
    	}
    	

    	if ($r->monto <= $monto_versus ) {

    		if ($r->monto == $monto_versus ) {
    			//dd("hola 1");	
    				$ab = new AbonoSalud;
    				$ab->user_id = Auth::user()->id;
    				$ab->activo = 'S';
    				$ab->dia = $f['dia'];
    				$ab->mes_id = $f['mes'];
    				$ab->anio_id = $anio->id;
    				$ab->prestamo_id = $psr->prestamo_id;
    				$ab->monto = $r->monto;
    				$ab->abono_tipo = $r->abono;
    				$ab->restante_abono = 0; // cero, porque se asume que el monto(input) es igual al monto del abono
    				if ($ab->save()) {
    					return ['estado'=>'success','mensaje'=>'abono realizado'];
    				}

    		}
    		if ($r->monto < $monto_versus) {
    			//dd("hola 2");
    			$ab = new AbonoSalud;
    				$ab->user_id = Auth::user()->id;
    				$ab->activo = 'S';
    				$ab->dia = $f['dia'];
    				$ab->mes_id = $f['mes'];
    				$ab->anio_id = $anio->id;
    				$ab->prestamo_id = $psr->prestamo_id;
    				$ab->monto = $r->monto;
    				$ab->abono_tipo = $r->abono;
    				$ab->restante_abono = $monto_versus - $r->monto; // cero, porque se asume que el monto(input) es igual al monto del abono
    				if ($ab->save()) {
    					return ['estado'=>'success','mensaje'=>'abono realizado'];
    				}
    		}
    	}
    	else{
    		return ['estado'=>'failed','mensaje'=>'el monto es mayor al abono'];
    	}
    }

    public function pagar_p_apuro_economico_retornable(Request $r)
    {
    	$fecha = $r->fecha;
    	$monto = $r->monto;
    	$p_apuro_id = $r->p_apuro_id;

    	$item = p_apuro_economico_retornable::find($p_apuro_id);
    	$ultimo_item = p_apuro_economico_retornable::where(['prestamo_id'=>$item->prestamo_id])->latest()->first();

    	if (($ultimo_item->estado_cuota == 'vigente' || $ultimo_item->estado_cuota == 'pagando' ) && $ultimo_item->numero_cuota < $ultimo_item->cuotas) {

    		$monto_restante = p_apuro_economico_retornable::where(['prestamo_id'=>$ultimo_item->prestamo_id])->min('monto_restante'); 


    		if($monto_restante == 0){
    			return ['estado'=>'failed','mensaje'=>'EL monto restante esta en monto 0, posiblemente el prestamo ya esté pagado'];
    		}

    		if($monto - ($ultimo_item->interes_total / $ultimo_item->cuotas) > $monto_restante){
    			return ['estado'=>'failed','mensaje'=>'EL monto ingresado es muy grande para el restante del prestamo ('.$monto_restante.')'];
    		}

    		$f = $this->div_fecha($fecha); // dividir fecha por dia, mes, anio;
    		$anio = $this->anio_tipo_id($f['anio']);

    		$estado = (($ultimo_item->monto_restante - ($monto - ($ultimo_item->interes_total / $ultimo_item->cuotas))) == 0)?'pagado' : 'pagando';

    		$paer = new p_apuro_economico_retornable;
    		$paer->prestamo_id = $ultimo_item->prestamo_id;
    		$paer->monto_restante = $ultimo_item->monto_restante - ($monto - ($ultimo_item->interes_total / $ultimo_item->cuotas)); //menos lo que paga el socio
    		$paer->numero_cuota = $ultimo_item->numero_cuota + 1; //sumamos la cuota que corresponde
    		$paer->cuotas = $ultimo_item->cuotas;
    		$paer->interes_total = $ultimo_item->interes_total;
    		$paer->interes_mensual = ($ultimo_item->interes_total / $ultimo_item->cuotas);
    		$paer->definicion = '1';
    		$paer->ingreso = $monto - ($ultimo_item->interes_total / $ultimo_item->cuotas);
    		$paer->egreso = 0;
    		$paer->anio_id = $anio->id;
    		$paer->mes_id = $f['mes'];
    		$paer->dia = $f['dia'];
    		$paer->user_id = Auth::user()->id;
    		$paer->activo = 'S';
    		$paer->estado_cuota = $estado; 

    		if ($paer->save()) {
    				
    			$monto_restante = p_apuro_economico_retornable::where(['prestamo_id'=>$paer->prestamo_id])->min('monto_restante'); 
	    		if($monto_restante == 0){

	    					$prestamo = CsPrestamo::find($paer->prestamo_id);
	    					$prestamo->estado_prestamo = 'pagado';
	    					$prestamo->save();

							return ['estado'=>'failed','mensaje'=>'Pago realizado, el monto restante ya esta en monto 0, se concidera la finalización del prestamo'];
	    		}

    			$prestamo = CsPrestamo::where(['activo'=>'S','id'=>$paer->prestamo_id])->first();
    			$prestamo->estado_prestamo = 'pagando';
    			$prestamo->save();

    			return ['estado'=>'success','mensaje'=>'Pago realizado correctamente!!'];
    		}
    	}else{
    		return ['estado'=>'failed','mensaje'=>'Puede que el prestamo ya esté finalizado'];
    	}

    }


    function insertar_p_aporte_economico($r)
    {
    	return "aporte economico";
    }



    public function listar_abonos_por_prestamo_id($id, $tipo)
    {
    	$abono = DB::select("SELECT 
						    	concat(asr.dia,' de ',m.descripcion,',',a.descripcion) as fecha,
						    	monto
							from abonos_salud_retornable as asr
							inner join mes as m on m.id = asr.mes_id
							inner join anio as a on a.id = asr.anio_id
							where prestamo_id = $id and abono_tipo = $tipo");

    	if (count($abono)>0) {
    		$retorna = [];
		    	 foreach ($abono as $a) {
		    	 	$retorna[] =  $a->fecha.' se cancelan '.number_format($a->monto,0,'.',',').' pesos';
		    	 }
		    	 return $retorna;
		    }
		    return "No existe registro";
    	//return AbonoSalud::where(['prestamo_id'=> $id, 'abono_tipo'=>$tipo])->get();
    }


    public function tabla_final_prestamo($anio, $mes)
    {
    	$monto_interes = DB::select("SELECT COALESCE(sum(interes_mensual),0) as interes_mensual  from p_apuro_economico_retornable
                                    where mes_id = $mes and anio_id = $anio and definicion = 1 and activo = 'S';");

		    	 $salud_retor = DB::select("SELECT COALESCE(sum(egreso),0)as egreso  from p_salud_retornable
											where mes_id = $mes and anio_id = $anio and definicion = 2");

				$apuro = DB::select("SELECT COALESCE(sum(egreso),0)as egreso  from p_apuro_economico_retornable
											where mes_id = $mes and anio_id = $anio and definicion = 2 and activo = 'S'");

				$aporte = DB::select("SELECT COALESCE(sum(egreso),0) as egreso from cs_prestamo
											where mes_id = $mes and anio_id = $anio and definicion = 2 and activo ='S' 
											and estado_prestamo ='egresado'");

		$monto_egreso = $salud_retor[0]->egreso + $apuro[0]->egreso + $aporte[0]->egreso;


				$salud_retor = DB::select("SELECT COALESCE(sum(ingreso),0)as ingreso  from p_salud_retornable
									where mes_id = $mes and anio_id = $anio and definicion = 1");

				$apuro = DB::select("SELECT COALESCE(sum(ingreso),0)as ingreso  from p_apuro_economico_retornable
									where mes_id = $mes and anio_id = $anio and definicion = 1");

				$abonos = DB::select("SELECT COALESCE(sum(monto),0) as monto from abonos_salud_retornable where mes_id = $mes and anio_id = $anio");

		$monto_ingreso = $salud_retor[0]->ingreso + $apuro[0]->ingreso;
		$monto_abonos = $abonos[0]->monto;

		return [
			'interes' => (int)$monto_interes[0]->interes_mensual,
			'egreso'  => (int)$monto_egreso,
			'ingreso' => (int)$monto_ingreso,
			'abonos'  => (int)$monto_abonos
		];
    }


    public function listar_prestamos_vigentes()
    {
    	$listar = CsPrestamo::prestamos_no_iniciados();
    	return $listar;
    }
    public function listar_prestamos_finalizados()
    {
    	$listar = CsPrestamo::prestamos_finalizados();
    	return $listar;
    }


    public function socio($id)
    {
    	$s = Socios::find($id);
    	if ($s) {
    		return $s->nombres .' '. $s->a_paterno .' '. $s->a_materno;
    	}
    	return '(nombre)';
    	
    }
    public function div_fecha($value)//funciona con input type date 
    {
    	$fecha = $value;
		$ano = substr($fecha, -10, 4);
		$mes = substr($fecha, -5, 2);
		$dia = substr($fecha, -2, 2);
		return [
			'anio' => $ano, 
			'mes'  => $mes, 
			'dia'  => $dia
		];
    }

     public function anio_tipo_id($value)
    {

    	$data = DB::table('anio')->where('descripcion', $value)->first();

    	if(!empty($data)){

    	 	return $data;
    	 }
    } 

    protected function guardarArchivo($archivo, $ruta)
    {
    	try{
	        $filenameext = $archivo->getClientOriginalName();
	        $filename = pathinfo($filenameext, PATHINFO_FILENAME);
	        $extension = $archivo->getClientOriginalExtension();
	        $nombreArchivo = $filename . '_' . time() . '.' . $extension;
	        $rutaDB = $ruta . $nombreArchivo;

	        $guardar = Storage::put($ruta . $nombreArchivo, (string) file_get_contents($archivo), 'public');

	        if ($guardar) {
	            return ['estado' =>  'success', 'archivo' => $rutaDB];
	        } else {
	            return ['estado' =>  'failed', 'mensaje' => 'error al guardar el archivo.'];
	        }
	    }catch (\Throwable $t) {
    			return ['estado' =>  'failed', 'mensaje' => 'error al guardar el archivo, posiblemente este dañado o no exista.'];
		}
    }

    public function validar_pdf($request)
	{
		$val = Validator::make($request->all(), 
		 	[

	            'archivo_documento' => 'required|mimes:pdf',
	        ],
	        [
	        	'archivo_documento.required' => 'El PDF es necesario',
	        	'archivo_documento.mimes' => 'El archivo no es PDF',
	        ]);

 
	        if ($val->fails()){ return ['estado' => 'failed_v', 'mensaje' => $val->errors()];}
	        return ['estado' => 'success', 'mensaje' => 'success'];
	}


    //   public function pago_abono(Request $r)
    // {
    // 	$psr = p_salud_retornable::find($r->p_salud_retornable_id); //item prestamo salud retornable;

    // 	$existe_abono = AbonoSalud::where(['prestamo_id'=>$psr->prestamo_id])->get();

    // 	if (count($existe_abono) < 3) { //si no estan los 3 abonos cancelados;

    		

    // 		// switch ((string)$r->abono) {
    // 		// 	case '1': //dia de sueldo
    // 		// 		//veirifcar que no exista monto en dia sueldo
    // 		// 	    $hay_dia_sueldo = false;
    // 		// 	    $monto_dia_sueldo = 0;
    // 		// 		if (count($existe_abono) > 0) {
    // 		// 			foreach ($existe_abono as $key) {
    // 		// 				if (!empty($key->monto_dia_sueldo)) { //en caso que exista monto dia sueldo en tabla abono
    // 		// 					$hay_dia_sueldo = true;
    // 		// 					$monto_dia_sueldo = $key->monto_dia_sueldo;
    // 		// 				}
    // 		// 			}

    // 		// 			if ($hay_dia_sueldo == true ) {
    // 		// 				return ['estado'=>'failed','mensaje'=>'Ya ha pagado antes este abono'];
    // 		// 				if ($monto_dia_sueldo < $psr->monto_dia_sueldo ) {
    // 		// 					# code...
    // 		// 				}
    // 		// 			}else{

    // 		// 			}
    // 		// 		}


    // 		// 	break;
    // 		// 	case '2'://termino conflicto
    // 		// 		 $hay_termino_conflicto = false;
    // 		// 		 $monto_termino_conflicto = 0;
    // 		// 		if (count($existe_abono) > 0) {
    // 		// 			foreach ($existe_abono as $key) {
    // 		// 				if (!empty($key->monto_termino_conflicto)) {
    // 		// 					$hay_termino_conflicto = true;
    // 		// 					$monto_termino_conflicto = $key->monto_termino_conflicto;
    // 		// 				}
    // 		// 			}
    // 		// 		}
    				
    // 		// 	break;
    // 		// 	case '3'://trimestral
    // 		// 		 $hay_trimestral = false;
    // 		// 		 $monto_trimestral = 0;
    // 		// 		if (count($existe_abono) > 0) {
    // 		// 			foreach ($existe_abono as $key) {
    // 		// 				if (!empty($key->monto_termino_trimestral)) {
    // 		// 					$hay_trimestral = true;
    // 		// 					$monto_trimestral = $key->monto_trimestral;
    // 		// 				}
    // 		// 			}
    // 		// 		}
    // 		// 	break;
    			
    // 		// 	default:
    // 		// 		# code...
    // 		// 		break;
    // 		// }
    // 	}
    // 	else{
    // 		return ['estado'=>'failed','mensaje'=>'Ya estan los tres abonos pagados'];
    // 	}








    // 	$dia_suledo = $psr->monto_dia_sueldo;
    // 	$trimestral = $psr->monto_trimestral;
    // 	$termino_conf = $psr->monto_termino_conflicto;

    
    // 		$abono = new AbonoSalud;

    // 		dd($psr);
    	

    	
    // }
    	
}
