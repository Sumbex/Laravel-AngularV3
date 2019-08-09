<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Socio_datos_basicos;
use App\SocioSituacion;

class PortalSocio extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = "socios";

    protected $fillable = [
        'rut', 'password'
    ];

    protected $hidden = [
        'password', 'activo', 'created_at', 'updated_at', 'fecha_egreso', 'rol'
    ];

    protected $guard = 'socio_api';

    public function validarDatos($request, $opcion)
    {
        switch ($opcion) {
            case 1:
                switch ($request->nombre_campo) {
                    case 'nombres':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'socio_id' => 'required',
                                'input' => 'required|min:0'
                            ],
                            [
                                'socio_id.required' => 'Debes pasar el ID del socio',
                                'input.required' => 'El nombre es necesario'
                            ]
                        );
                        break;
                    case 'a_paterno':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'socio_id' => 'required',
                                'input' => 'required|min:0'
                            ],
                            [
                                'socio_id.required' => 'Debes pasar el ID del socio',
                                'input.required' => 'El apellido es necesario'
                            ]
                        );
                        break;
                    case 'a_materno':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'socio_id' => 'required',
                                'input' => 'required|min:0'
                            ],
                            [
                                'socio_id.required' => 'Debes pasar el ID del socio',
                                'input.required' => 'El apellido es necesario'
                            ]
                        );
                        break;
                    case 'fecha_nacimiento':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'socio_id' => 'required',
                                'input' => 'required'
                            ],
                            [
                                'socio_id.required' => 'Debes pasar el ID del socio',
                                'input.required' => 'La fecha es necesaria'
                            ]
                        );
                        break;

                    default:
                        # code...
                        break;
                }
                break;

            default:
                # code...
                break;
        }

        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    protected function loginSocios($request)
    {
        try {
            if (!$this->validarRut($request->rut)) {
                return ['status' => 'failed', 'mensaje' => 'El rut ingresado no es valido.'];
            } else {
                $socio = PortalSocio::where('rut', $request->rut)->first();
                /* dd(is_null($socio->fecha_egreso)); */
                if (is_null($socio->fecha_egreso)) {
                    if (Hash::check($request->password, $socio->password)) {
                        /* Config */
                        config()->set('auth.defaults.guard', 'socio_api');
                        \Config::set('jwt.user', App\PortalSocio::class);
                        \Config::set('auth.providers.users.model', \App\PortalSocio::class);
                        $credentials = $request->only('rut', 'password');

                        if (!$token = JWTAuth::attempt($credentials)) {
                            return response([
                                'status' => 'error',
                                'error' => 'invalid.credentials',
                                'msg' => 'Invalid Credentials.'
                            ], 400);
                        }
                        return response([
                            'status' => 'success',
                            'token' => $token,
                            /* 'user' =>  JWTAuth::user() */
                        ])
                            ->header('Authorization', $token);
                    }
                    return response(['status' => 'failed', 'mensaje' => 'La contraseña ingresado no es valida.']);
                } else {
                    return response(['status' => 'failed', 'mensaje' => 'El socio ya no se encuentra activo en el sindicato.']);
                }
            }
        } catch (\ErrorException $e) {
            return ['status' => 'failed', 'Se ha producido un error, verifique si el rut es correcto o existe en la base de datos'];
        }
    }

    protected function validarRut($rut)
    {
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $dv  = substr($rut, -1);
        $numero = substr($rut, 0, strlen($rut) - 1);
        $i = 2;
        $suma = 0;
        foreach (array_reverse(str_split($numero)) as $v) {
            if ($i == 8)
                $i = 2;
            $suma += $v * $i;
            ++$i;
        }
        $dvr = 11 - ($suma % 11);

        if ($dvr == 11)
            $dvr = 0;
        if ($dvr == 10)
            $dvr = 'K';
        if ($dvr == strtoupper($dv))
            return true;
        else
            return false;
    }

    protected function socioLogeado()
    {

        $socio = PortalSocio::find(Auth::guard('socio_api')->user()->id);
        /* dd($socio); */
        return $socio;
    }

    /* protected function div_fecha($value)
    {
        $fecha = $value;
        $ano = substr($fecha, -10, 4);
        $mes = substr($fecha, -5, 2);
        $dia = substr($fecha, -2, 2);
        return [
            'anio' => $ano, 'mes' => $mes, 'dia' => $dia
        ];
    }
    protected function anio_tipo_id($value)
    {
        return DB::table('anio')->where('descripcion', $value)->first();
    }

    protected function mes_tipo_id($value)
    {
        return DB::table('mes')->where('id', $value)->first();
    } */

    protected function verificarSocio($id)
    {
        $verificar = DB::table('socios')
            ->where([
                'id' => $id,
                'activo' => 'S'
            ])
            ->first();
        if (is_null($verificar->fecha_egreso)) {
            return ['estado' => 'success'];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Ya no te encuentras activo en el sindicato, si no es asi regulariza tu situacion.'];
        }
    }

    protected function modificarDatosSocios($request)
    {
        $validarDatos = $this->validarDatos($request, 1);
        if ($validarDatos['estado'] == 'success') {
            $verificar = $this->verificarSocio($request->socio_id);
            if ($verificar['estado'] == 'success') {
                $socio = PortalSocio::find($request->socio_id);
                switch ($request->nombre_campo) {
                    case 'nombres':
                        $socio->nombres = $request->input;
                        if ($socio->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Nombre Actualizado.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    case 'a_paterno':
                        $socio->a_paterno = $request->input;
                        if ($socio->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Apellido Actualizado.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    case 'a_materno':
                        $socio->a_materno = $request->input;
                        if ($socio->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Apellido Actualizado.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    case 'fecha_nacimiento':
                        $socio->fecha_nacimiento = $request->input;
                        if ($socio->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Fecha de Nacimiento Actualizada.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            } else {
                return $verificar;
            }
        } else {
            return $validarDatos;
        }
    }

    protected function traerDatosBasicosSocios()
    {
        $socio = DB::table('socios_datos_basicos as sdb')
            ->select([
                'sdb.id',
                'sdb.socio_id',
                'sdb.direccion',
                /* 'sbd.direccion_2', */
                'sdb.telefono',
                'sdb.celular',
                'sdb.anexo',
                'sdb.email_1 as email_personal',
                'sdb.email_2 as email_corporativo',
                'sdb.cargo_planta',
                'sdb.cargo_comision_sindicato',
                'sdb.casa_propia',
                'sdb.rol_turno',
                'sdb.estado_civil_id',
                'sdb.conyuge',
                'ss.numero_cuenta',
                'ss.tipo_cuenta_banco_id',
                'ss.banco',
                'ss.isapre_fonasa',
                'ss.grupo_sangre'
            ])
            ->join('socio_situacion as ss', 'ss.socio_id', 'sdb.socio_id')
            ->join('socios as s', 's.id', 'sdb.socio_id')
            ->where([
                's.activo' => 'S',
                's.id' => $this->socioLogeado()->id
            ])
            ->get();

        if (!$socio->isEmpty()) {
            return $socio;
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes datos ingresados.'];
        }
    }

    protected function ingresarDatosBasicosSocio($request)
    {
        $verificar = $this->verificarSocio($this->socioLogeado()->id);
        if ($verificar['estado'] == 'success') {
            $socioDB = new Socio_datos_basicos;
            $socioDB->socio_id = $this->socioLogeado()->id;
            $socioDB->direccion = $request->direccion;
            $socioDB->telefono = $request->telefono;
            $socioDB->celular = $request->celular;
            $socioDB->anexo = $request->anexo;
            $socioDB->email_1 = $request->email_1;
            $socioDB->email_2 = $request->email_2;
            $socioDB->cargo_planta = $request->cargo_planta;
            $socioDB->cargo_comision_sindicato = $request->cargo_comision_sindicato;
            $socioDB->activo = 'S';
            $socioDB->casa_propia = $request->casa_propia;
            $socioDB->rol_turno = $request->rol_turno;
            $socioDB->estado_civil_id = $request->estado_civil_id;
            $socioDB->conyuge = $request->conyuge;
            if ($socioDB->save()) {
                $socioS = new SocioSituacion;
                $socioS->socio_id = $this->socioLogeado()->id;
                $socioS->numero_cuenta = $request->numero_cuenta;
                $socioS->tipo_cuenta_banco_id = $request->tipo_cuenta_banco_id;
                $socioS->banco = $request->banco;
                $socioS->isapre_fonasa = $request->isapre_fonasa;
                $socioS->grupo_sangre = $request->grupo_sangre;
                $socioS->activo = 'S';
                if ($socioS->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Datos Ingresados Correctamente.'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                }
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return $verificar;
        }
    }
}
