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
use App\SocioConyuge;
use App\SocioBeneficiario;
use App\User;

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
        'password', 'activo', 'created_at', 'updated_at', 'fecha_egreso'/* , 'rol' */
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
            case 2:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'direccion' => 'required|string',
                        'telefono' => 'string',
                        'celular' => 'string',
                        'anexo' => 'string',
                        'email_1' => 'required|email', //'unique:users,email_address'
                        'email_2' => 'email', //'unique:users,email_address'
                        'cargo_planta' => 'string',
                        'cargo_comision_sindicato' => 'integer||min:0',
                        'casa_propia' => 'string',
                        'rol_turno' => 'string',
                        'estado_civil_id' => 'integer||min:0',
                        'conyuge' => 'string',
                        'numero_cuenta' => 'string',
                        'tipo_cuenta_banco_id' => 'integer||min:0',
                        'banco' => 'string',
                        'isapre_fonasa' => 'string',
                        'grupo_sangre' => 'string',

                    ],
                    [
                        'direccion.required' => 'Debes ingresar tu direccion.',
                        'email_1.required' => 'Debes ingresar tu correo personal.',
                        'email_1.email' => 'Debes ingresar un correo valido.',
                        'email_2' => 'Debes ingresar un correo valido.',
                    ]
                );
                break;
            case 3:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'rut' => 'required',
                        'password' => 'required'
                    ],
                    [
                        'rut.required' => 'Debes ingresar tu rut.',
                        'password.required' => 'Debes ingresar tu contrasena.',
                    ]
                );
                break;
            case 4:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'rut' => 'required',
                        'fecha_nacimiento' => 'required',
                        'nombres' => 'required|string',
                        'apellido_paterno' => 'required|string',
                        'apellido_materno' => 'required|string',
                        'direccion' => 'required|string',
                        'celular' => 'required|string'
                    ],
                    [
                        'rut.required' => 'Debes ingresar el rut.',
                        'fecha_nacimiento.required' => 'Debes ingresar la fecha de nacimiento.',
                        'nombres.required' => 'Debes ingresar los nombres.',
                        'apellido_paterno.required' => 'Debes ingresar el apellido',
                        'apellido_materno.required' => 'Debes ingresar el apellido',
                        'direccion.required' => 'Debes ingresar la direccion',
                        'celular.required' => 'Debes ingresar el numero de celular'
                    ]
                );
                break;

            case 5:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'relacion' => 'required|string',
                        'rut' => 'required',
                        'fecha_nacimiento' => 'required',
                        'nombres' => 'required|string',
                        'apellido_paterno' => 'required|string',
                        'apellido_materno' => 'required|string',
                        'direccion' => 'required|string',
                        'celular' => 'required|string'
                    ],
                    [
                        'relacion.required' => 'Debes ingresar su relacion.',
                        'rut.required' => 'Debes ingresar el rut.',
                        'fecha_nacimiento.required' => 'Debes ingresar la fecha de nacimiento.',
                        'nombres.required' => 'Debes ingresar los nombres.',
                        'apellido_paterno.required' => 'Debes ingresar el apellido',
                        'apellido_materno.required' => 'Debes ingresar el apellido',
                        'direccion.required' => 'Debes ingresar la direccion',
                        'celular.required' => 'Debes ingresar el numero de celular'
                    ]
                );
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
            $validarDatos = $this->validarDatos($request, 3);
            if ($validarDatos['estado'] == 'success') {
                if (!$this->validarRut($request->rut)) {
                    return ['status' => 'failed', 'mensaje' => 'El rut ingresado no es valido.'];
                } else {

                    $socio = PortalSocio::where('rut', $request->rut)->first();

                    /* dd(is_null($socio->fecha_egreso)); */
                    if (is_null($socio->fecha_egreso)) {

                        $user = User::where('rut', $socio->rut)->first();
                        /* dd($user); */
                        if (Hash::check($request->password, $user->password)) {
                            /* Config */
                            config()->set('auth.defaults.guard', 'socio_api');
                            \Config::set('jwt.user', 'App\User');
                            \Config::set('auth.providers.users.model', \App\User::class);


                            $credentials = $request->only('rut', 'password');

                            // dd($credentials);
                            if (!$token = JWTAuth::attempt($credentials)) {
                                return response([
                                    'status' => 'error',
                                    'error' => 'invalid.credentials',
                                    'msg' => 'Invalid Credentials.'
                                ], 400);
                            }

                            /* $test = \Auth::guard('socio_api');
                            dd($test); */

                            return response([
                                'status' => 'success',
                                'token' => $token,
                                'user' =>  JWTAuth::user()
                            ])
                                ->header('Authorization', $token);
                        }
                        return response(['status' => 'failed', 'mensaje' => 'La contrasena ingresado no es valida.']);
                    } else {
                        return response(['status' => 'failed', 'mensaje' => 'El socio ya no se encuentra activo en el sindicato.']);
                    }
                }
            } else {
                return $validarDatos;
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
        /* dd(Auth::guard('socio_api')->user()); */
        $socio = PortalSocio::where('rut', Auth::guard('socio_api')->user()->rut)->get();
        /* dd($socio); */
        foreach ($socio as $key) {
            $key->rol = Auth::guard()->user()->rol;
        }
        return $socio[0];
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
        $verificar = $this->verificarSocio($this->socioLogeado()->id);
        if ($verificar['estado'] == 'success') {
            $validarDatos = $this->validarDatos($request, 1);
            if ($validarDatos['estado'] == 'success') {
                $socio = PortalSocio::find($this->socioLogeado()->id);
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
                return $validarDatos;
            }
        } else {
            return $verificar;
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

        $estados = [];
        if (!$socio->isEmpty()) {
            if (!is_null($socio[0]->grupo_sangre)) {
                $estados['grupo_sangre'] = true;
            }
            return ['estado' => 'success', 'socio' => $socio, 'estados' => $estados];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes datos ingresados.'];
        }
    }

    protected function ingresarDatosBasicosSocio($request)
    {
        $tienesDatos = $this->traerDatosBasicosSocios();
        if ($tienesDatos['estado'] == 'failed') {
            $verificar = $this->verificarSocio($this->socioLogeado()->id);
            if ($verificar['estado'] == 'success') {
                $validarDatos = $this->validarDatos($request, 2);
                if ($validarDatos['estado'] == 'success') {
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
                    return $validarDatos;
                }
            } else {
                return $verificar;
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Ya tiene datos ingresados, si le faltaron datos por ingresar utilize el otro formulario.'];
        }
    }

    protected function modificarDatosBasicosSocio($request)
    {
        $tienesDatos = $this->traerDatosBasicosSocios();
        if ($tienesDatos['estado'] == 'success') {
            $verificar = $this->verificarSocio($this->socioLogeado()->id);
            if ($verificar['estado'] == 'success') {
                switch ($request->tipo) {
                    case 1:
                        $socioDB = Socio_datos_basicos::where([
                            'activo' => 'S',
                            'socio_id' => $this->socioLogeado()->id
                        ])
                            ->first();

                        switch ($request->nombre_campo) {
                            case 'direccion':
                                $socioDB->direccion = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Direccion actualizada Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'telefono':
                                $socioDB->telefono = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Telefono actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'celular':
                                $socioDB->celular = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Celular actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'anexo':
                                $socioDB->anexo = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Anexo actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'email_1':
                                $socioDB->email_1 = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Correo Personal actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'email_2':
                                $socioDB->email_2 = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Correo Corporativo actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'cargo_planta':
                                $socioDB->cargo_planta = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Cargo Planta actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'cargo_comision_sindicato':
                                $socioDB->cargo_comision_sindicato = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Cargo Comision Sindicato actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'casa_propia':
                                $socioDB->casa_propia = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Casa propia actualizada Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'rol_turno':
                                $socioDB->rol_turno = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Rol de Turno actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'estado_civil_id':
                                $socioDB->estado_civil_id = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Estado civil actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'conyuge':
                                $socioDB->conyuge = $request->input;
                                if ($socioDB->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Conyuge actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 2:
                        $socioS = SocioSituacion::where([
                            'activo' => 'S',
                            'socio_id' => $this->socioLogeado()->id
                        ])
                            ->first();

                        switch ($request->nombre_campo) {
                            case 'numero_cuenta':
                                $socioS->numero_cuenta = $request->input;
                                if ($socioS->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Numero de cuenta actualizada Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'tipo_cuenta_banco_id':
                                $socioS->tipo_cuenta_banco_id = $request->input;
                                if ($socioS->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Tipo de Cuenta actualizada Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'banco':
                                $socioS->banco = $request->input;
                                if ($socioS->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Banco actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'isapre_fonasa':
                                $socioS->isapre_fonasa = $request->input;
                                if ($socioS->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Isapre/Fonasa actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                                break;
                            case 'grupo_sangre':
                                $socioS->grupo_sangre = $request->input;
                                if ($socioS->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Grupo de sangre actualizado Correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
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
            } else {
                return $verificar;
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no haz ingresado tus datos, utilize el otro formulario para ingresarlos.'];
        }
    }

    protected function traerDatosConyuge()
    {
        $conyuge = DB::table('socio_conyuge')
            ->select([
                'rut',
                'fecha_nacimiento',
                'nombres',
                'apellido_paterno',
                'apellido_materno',
                'direccion',
                'celular'
            ])
            ->where([
                'activo' => 'S',
                'socio_id' => $this->socioLogeado()->id
            ])
            ->get();

        if (!$conyuge->isEmpty()) {
            return ['estado' => 'success', 'conyuge' => $conyuge];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes datos ingresados.'];
        }
    }

    protected function ingresarConyugeSocio($request)
    {
        $tienesDatos = $this->traerDatosConyuge();
        if ($tienesDatos['estado'] == 'failed') {
            $verificar = $this->verificarSocio($this->socioLogeado()->id);
            if ($verificar['estado'] == 'success') {
                $validarDatos = $this->validarDatos($request, 4);
                if ($validarDatos['estado'] == 'success') {
                    $conyuge = new SocioConyuge;
                    $conyuge->socio_id = $this->socioLogeado()->id;
                    $conyuge->rut = $request->rut;
                    $conyuge->fecha_nacimiento = $request->fecha_nacimiento;
                    $conyuge->nombres = $request->nombres;
                    $conyuge->apellido_paterno = $request->apellido_materno;
                    $conyuge->direccion = $request->direccion;
                    $conyuge->celular = $request->celular;
                    $conyuge->activo = 'S';
                    if ($conyuge->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Datos Ingresados Correctamente.'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                    }
                } else {
                    return $validarDatos;
                }
            } else {
                return $verificar;
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Ya tienes datos ingresados, si deseas modificarlos dirigete al sindicato.'];
        }
    }

    protected function traerDatosBeneficiariosSocios()
    {
        $beneficiario = DB::table('socio_beneficiario')
            ->select([
                'relacion',
                'rut',
                'fecha_nacimiento',
                'nombres',
                'apellido_paterno',
                'apellido_materno',
                'direccion',
                'celular',
                'cobro_beneficion',
                'fecha_cobro_beneficio'
            ])
            ->where([
                'activo' => 'S',
                'socio_id' => $this->socioLogeado()->id
            ])
            ->get();

        if (!$beneficiario->isEmpty()) {
            for ($i = 0; $i < count($beneficiario); $i++) {
                if (is_null($beneficiario[$i]->fecha_cobro_beneficio)) {
                    $beneficiario[$i]->fecha_cobro_beneficio = 'Aun no se ha realizado el cobro.';
                }
            }
            return ['estado' => 'success', 'beneficiario' => $beneficiario];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes datos ingresados.'];
        }
    }

    protected function ingresarBeneficiariosSocio($request)
    {
        $verificar = $this->verificarSocio($this->socioLogeado()->id);
        if ($verificar['estado'] == 'success') {
            $validarDatos = $this->validarDatos($request, 5);
            if ($validarDatos['estado'] == 'success') {
                $beneficiario = new SocioBeneficiario;
                $beneficiario->socio_id = $this->socioLogeado()->id;
                $beneficiario->relacion = $request->relacion;
                $beneficiario->rut = $request->rut;
                $beneficiario->fecha_nacimiento = $request->fecha_nacimiento;
                $beneficiario->nombres = $request->nombres;
                $beneficiario->apellido_paterno = $request->apellido_paterno;
                $beneficiario->apellido_materno = $request->apellido_materno;
                $beneficiario->direccion = $request->direccion;
                $beneficiario->celular = $request->celular;
                $beneficiario->activo = 'S';
                $beneficiario->cobro_beneficio = 'N';
                if ($beneficiario->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Datos Ingresados Correctamente.'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                }
            } else {
                return $validarDatos;
            }
        } else {
            return $verificar;
        }
    }

    protected function traerDatosCargasSocio()
    {
        $cargas = DB::table('cargas_legales_socio')
            ->select([
                'rut',
                'fecha_nacimiento',
                'nombres',
                'apellido_paterno',
                'apellido_materno',
                'direccion',
                'celular'
            ])
            ->where([
                'activo' => 'S',
                'socio_id' => $this->socioLogeado()->id
            ])
            ->get();

        if (!$cargas->isEmpty()) {
            return ['estado' => 'success', 'cargas' => $cargas];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes datos ingresados.'];
        }
    }

    protected function ingresarDatosCargasSocio($request)
    {
        //
    }

    protected function crearUsuarioSocio($request)
    {
        $traerSocio = PortalSocio::find($request->id);

        if (!is_null($traerSocio)) {
            if (is_null($traerSocio->fecha_egreso)) {
                $user = User::where('rut', $traerSocio->rut)->first();
                if (is_null($user)) {
                    $crear = new User;
                    $crear->name = $traerSocio->nombres;
                    $crear->nombres = $traerSocio->nombres;
                    $crear->a_paterno = $traerSocio->a_paterno;
                    $crear->a_materno = $traerSocio->a_materno;
                    $crear->email = 'correo@prueba.cl';
                    $pass = substr($traerSocio->rut, -5, 4);
                    $crear->password = bcrypt($pass);
                    $crear->rut = $traerSocio->rut;
                    $crear->rol = '10';
                    if ($crear->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Credenciales de acceso creadas correctamente.'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Error al crear las credenciales del socio.'];
                    }
                } else {
                    $user->rol = '5';
                    if ($user->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Credenciales de acceso mixto creadas correctamente.'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Error al crear las credenciales.'];
                    }
                }
            } else {
                return ['estado' => 'failed', 'mensaje' => 'El socio ya no se encuentra en el sindicato.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Socio no encontrado.'];
        }
    }

    protected function borrarUsuarioSocio($request)
    {
        $traerSocio = PortalSocio::find($request->id);

        if (!is_null($traerSocio)) {
            if (!is_null($traerSocio->fecha_egreso)) {
                $user = User::where('rut', $traerSocio->rut)->first();
                if (!is_null($user)) {
                    if ($user->rol == 5) {
                        $user->rol = '1';
                        if ($user->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Credenciales de acceso como Socio eliminadas correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'Error al eliminar las credenciales del socio.'];
                        }
                    } else {
                        if ($user->delete()) {
                            return ['estado' => 'success', 'mensaje' => 'Credenciales de acceso eliminadas correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'Error al eliminar las credenciales del socio.'];
                        }
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'El socio aun no tiene credenciales de acceso.'];
                }
            } else {
                return ['estado' => 'failed', 'mensaje' => 'El socio aun se encuentra activo en el sindicato.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Socio no encontrado.'];
        }
    }

    protected function crearUsuariosAdmin($request)
    {
        $crear = new User;
        $crear->name = $request->nombres;
        $crear->nombres = $request->nombres;
        $crear->a_paterno = $request->a_paterno;
        $crear->a_materno = $request->a_materno;
        $crear->email = $request->email;
        $crear->password = bcrypt($request->password);
        $crear->rut = $request->rut;
        $crear->rol = '1';
        if ($crear->save()) {
            return ['estado' => 'success', 'mensaje' => 'Credenciales de acceso creadas correctamente.'];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Error al crear las credenciales.'];
        }
    }

    protected function borrarUsuariosAdmin($request)
    {
        $user = User::find($request->id);
        if ($user->delete()) {
            return ['estado' => 'success', 'mensaje' => 'Credenciales de acceso eliminadas correctamente.'];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Error al eliminar las credenciales.'];
        }
    }
}
