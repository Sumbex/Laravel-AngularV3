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
use App\SocioCarga;
use App\SocioPadresSuegros;
use Illuminate\Support\Facades\Storage;

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
                                'input' => 'required|min:0'
                            ],
                            [
                                'input.required' => 'El nombre es necesario'
                            ]
                        );
                        break;
                    case 'a_paterno':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'input' => 'required|min:0'
                            ],
                            [
                                'input.required' => 'El apellido es necesario'
                            ]
                        );
                        break;
                    case 'a_materno':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'input' => 'required|min:0'
                            ],
                            [
                                'input.required' => 'El apellido es necesario'
                            ]
                        );
                        break;
                    case 'fecha_nacimiento':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'input' => 'required'
                            ],
                            [
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
                        'direccion' => 'required',
                        'telefono' => 'required',
                        'celular' => 'required',
                        'anexo' => 'required',
                        'email_1' => 'required|email', //'unique:users,email_address'
                        'email_2' => 'required|email', //'unique:users,email_address'
                        'cargo_planta' => 'required',
                        'cargo_comision_sindicato' => 'required',
                        'casa_propia' => 'required',
                        'rol_turno' => 'required',
                        'estado_civil_id' => 'required',
                        'conyuge' => 'required',
                        'numero_cuenta' => 'required',
                        'tipo_cuenta_banco_id' => 'required',
                        'banco' => 'required',
                        'isapre_fonasa' => 'required',
                        'grupo_sangre' => 'required',

                    ],
                    [
                        'direccion.required' => 'Debes ingresar tu direccion.',
                        'telefono.required' => 'Debes ingresar tu n° telefonico.',
                        'celular.required' => 'Debes ingresar tu n° de celular.',
                        'anexo.required' => 'Debes ingresar tu anexo.',
                        'email_1.required' => 'Debes ingresar tu correo personal.',
                        'email_1.email' => 'Debes ingresar un correo valido.',
                        'email_2.required' => 'Debes ingresar tu correo empresarial.',
                        'email_2.email' => 'Debes ingresar un correo valido.',
                        'cargo_planta.required' => 'Debes ingresar tu cargo.',
                        'cargo_comision_sindicato.required' => 'Debes seleccionar tu cargo en el sindicato.',
                        'casa_propia.required' => 'Debes seleccionar si tienes casa propia o no.',
                        'rol_turno.required' => 'Debes seleccionar si trabajas por turno o no',
                        'estado_civil_id.required' => 'Debes seleccionar tu estado civil.',
                        'conyuge.required' => 'Debes seleccionar si tienes conyuge o no.',
                        'numero_cuenta.required' => 'Debes ingresar tu n° de cuenta.',
                        'tipo_cuenta_banco_id.required' => 'Debes seleccionar tu tipo de cuenta.',
                        'banco.required' => 'Debes ingresar tu banco.',
                        'isapre_fonasa.required' => 'Debes ingresar tu Isapre/Fonasa.',
                        'grupo_sangre.required' => 'Debes ingresar tu grupo de sangre.'
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
                        'celular' => 'required|string',
                        'archivo' => 'required|file|mimes:pdf'
                    ],
                    [
                        'rut.required' => 'Debes ingresar el rut.',
                        'fecha_nacimiento.required' => 'Debes ingresar la fecha de nacimiento.',
                        'nombres.required' => 'Debes ingresar los nombres.',
                        'apellido_paterno.required' => 'Debes ingresar el apellido',
                        'apellido_materno.required' => 'Debes ingresar el apellido',
                        'direccion.required' => 'Debes ingresar la direccion',
                        'celular.required' => 'Debes ingresar el numero de celular',
                        'archivo.required' => 'Debe seleccionar un archivo.',
                        'archivo.file' => 'Lo seleccionado debe ser un archivo.',
                        'archivo.mimes' => 'El archivo debe ser extension PDF.'
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

            case 6:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'password' => 'required',
                        'new_password' => 'required|required_with:conf_new_password|same:conf_new_password',
                        'conf_new_password' => 'required'
                    ],
                    [
                        'password.required' => 'Debe ingresar su contrasena actual.',
                        'new_password.required' => 'Debe ingresar su contrasena nueva.',
                        'conf_new_password.required' => 'Confirme su nueva contraseña.',
                        'new_password.same' => 'Las contrasenas ingresadas no son iguales.'


                    ]
                );
                break;

            case 7:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'relacion_socio_id' => 'required|string',
                        'rut' => 'required',
                        'fecha_nacimiento' => 'required',
                        'nombres' => 'required|string',
                        'apellido_paterno' => 'required|string',
                        'apellido_materno' => 'required|string',
                        'direccion' => 'required|string',
                        'celular' => 'required|string'
                    ],
                    [
                        'relacion_socio_id.required' => 'Debes ingresar su relacion.',
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

            case 8:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'tipo_carga_id' => 'required|string',
                        'rut' => 'required',
                        'fecha_nacimiento' => 'required',
                        'nombres' => 'required|string',
                        'apellido_paterno' => 'required|string',
                        'apellido_materno' => 'required|string',
                        'direccion' => 'required|string',
                        'celular' => 'required|string',
                        'archivo' => 'required|file|mimes:pdf'
                    ],
                    [
                        'tipo_carga_id.required' => 'Debes seleccionar el tipo.',
                        'rut.required' => 'Debes ingresar el rut.',
                        'fecha_nacimiento.required' => 'Debes ingresar la fecha de nacimiento.',
                        'nombres.required' => 'Debes ingresar los nombres.',
                        'apellido_paterno.required' => 'Debes ingresar el apellido',
                        'apellido_materno.required' => 'Debes ingresar el apellido',
                        'direccion.required' => 'Debes ingresar la direccion',
                        'celular.required' => 'Debes ingresar el numero de celular',
                        'archivo.required' => 'Debe seleccionar un archivo.',
                        'archivo.file' => 'Lo seleccionado debe ser un archivo.',
                        'archivo.mimes' => 'El archivo debe ser extension PDF.'
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

                        if ($user->rol == 5 || $user->rol == 10) {
                            if (Hash::check($request->password, $user->password)) {
                                //$credentials = $request->only('email', 'password');
                                if (!$token = JWTAuth::fromUser($user)) {
                                    return response([
                                        'status' => 'error',
                                        'error' => 'invalid.credentials',
                                        'msg' => 'Invalid Credentials.'
                                    ], 400);
                                }
                                return response([
                                    'status' => 'success',
                                    'token' => $token,
                                    'rol' => (string) $user->rol,
                                ])
                                    ->header('Authorization', $token);
                            }
                            return response(['status' => 'failed', 'mensaje' => 'La contrasena ingresada no es valida.']);
                        } else {
                            return response(['status' => 'failed', 'mensaje' => 'Tu usuario no tiene permisos para acceder a este lugar.']);
                        }
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
        try {
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
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function socioLogeado()
    {
        /* dd(Auth::guard()->user()); */
        $socio = PortalSocio::where('rut', Auth::guard()->user()->rut)->get();
        /* dd($socio); */
        foreach ($socio as $key) {
            $key->rol = Auth::guard()->user()->rol;
        }
        return $socio[0];
    }

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

    protected function cambiarContrasena($request)
    {
        $verificar = $this->verificarSocio($this->socioLogeado()->id);
        if ($verificar['estado'] == 'success') {
            $validarDatos = $this->validarDatos($request, 6);
            if ($validarDatos['estado'] == 'success') {
                $user = User::find(Auth::guard()->user()->id);
                //dd(Hash::check($request->password, $user->password));
                if (Hash::check($request->password, $user->password)) {
                    $user->password = bcrypt($request->new_password);
                    if ($user->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Contrasena actualizada, ahora su sesion se cerrara para que el cambio sea correcto.'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                    }
                } else {
                    return response(['status' => 'failed', 'mensaje' => 'La contrasena ingresada no es valida.']);
                }
            } else {
                return $validarDatos;
            }
        } else {
            return $verificar;
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

    protected function guardarArchivo($archivo, $ruta)
    {
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
            return ['estado' => 'success', 'socio' => $socio];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes datos ingresados.'];
        }
    }

    protected function traerArchivoResumen()
    {
        $archivo = DB::table('socios_datos_basicos')
            ->select([
                'archivo'
            ])
            ->where([
                'activo' => 'S',
                'socio_id' => $this->socioLogeado()->id
            ])
            ->get();

        if (!$archivo->isEmpty()) {
            if (!is_null($archivo[0]->archivo)) {
                return ['estado' => 'success', 'archivo' => $archivo[0]->archivo];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'Aun no tienes tu archivo resumen ingresado.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes tu archivo resumen ingresado.'];
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
                'celular',
                'archivo'
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
                    $validarRut = $this->validarRut($request->rut);
                    if ($validarRut == true) {
                        $conyuge = new SocioConyuge;
                        $conyuge->socio_id = $this->socioLogeado()->id;
                        $conyuge->rut = $request->rut;
                        $conyuge->fecha_nacimiento = $request->fecha_nacimiento;
                        $conyuge->nombres = $request->nombres;
                        $conyuge->apellido_paterno = $request->apellido_paterno;
                        $conyuge->apellido_materno = $request->apellido_materno;
                        $conyuge->direccion = $request->direccion;
                        $conyuge->celular = $request->celular;
                        $conyuge->activo = 'S';
                        $guardarArchivo = $this->guardarArchivo($request->archivo, 'ArchivosSocios/ArchivosConyuge/');
                        if ($guardarArchivo['estado'] == "success") {
                            $conyuge->archivo = 'storage/' . $guardarArchivo['archivo'];
                        } else {
                            return $guardarArchivo;
                        }
                        if ($conyuge->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Datos Ingresados Correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'El rut ingresado no es valido.'];
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
                'cobro_beneficio',
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

    protected function verificarBeneficiarioIngresado($rut)
    {
        $beneficiario = DB::table('socio_beneficiario')
            ->select([
                'rut',
                'socio_id',
                DB::raw("concat(nombres,' ',apellido_paterno,' ',apellido_materno) as nombre")
            ])
            ->join('relacion_socio as rc', 'rc.id', 'pss.relacion_socio_id')
            ->where([
                'activo' => 'S',
                'socio_id' => $this->socioLogeado()->id,
                'rut' => $rut
            ])
            ->get();

        if (!$beneficiario->isEmpty()) {
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
                $validarRut = $this->validarRut($request->rut);
                if ($validarRut == true) {
                    $tienesDatos = $this->verificarRelacionIngresada($request->relacion_socio_id);
                    if ($tienesDatos['estado'] == 'failed') {
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
                        return ['estado' => 'failed', 'mensaje' => 'Ya tienes ingresado al beneficiario ' . $tienesDatos['beneficiario'][0]->nombre . '.'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'El rut ingresado no es valido.'];
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
                'tipo_carga_id',
                'rut',
                'fecha_nacimiento',
                'nombres',
                'apellido_paterno',
                'apellido_materno',
                'direccion',
                'celular',
                'establecimiento',
                'archivo'
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
        $verificar = $this->verificarSocio($this->socioLogeado()->id);
        if ($verificar['estado'] == 'success') {
            $validarDatos = $this->validarDatos($request, 8);
            if ($validarDatos['estado'] == 'success') {
                $validarRut = $this->validarRut($request->rut);
                if ($validarRut == true) {
                    $edad = \Carbon\Carbon::parse($request->fecha_nacimiento)->age;
                    //dd($edad);
                    if ($edad <= 24) {
                        $carga = new SocioCarga;
                        $carga->socio_id = $this->socioLogeado()->id;
                        $carga->tipo_carga_id = $request->tipo_carga_id;
                        $carga->rut = $request->rut;
                        $carga->fecha_nacimiento = $request->fecha_nacimiento;
                        $carga->nombres = $request->nombres;
                        $carga->apellido_paterno = $request->apellido_paterno;
                        $carga->apellido_materno = $request->apellido_materno;
                        $carga->direccion = $request->direccion;
                        $carga->celular = $request->celular;
                        $carga->establecimiento = $request->establecimiento;
                        $carga->activo = 'S';
                        $guardarArchivo = $this->guardarArchivo($request->archivo, 'ArchivosSocios/ArchivosCargas/');
                        if ($guardarArchivo['estado'] == "success") {
                            $carga->archivo = 'storage/' . $guardarArchivo['archivo'];
                        } else {
                            return $guardarArchivo;
                        }
                        if ($carga->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Datos Ingresados Correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'La carga que intentas ingresar ya supera la mayoria de edad legal.'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'El rut ingresado no es valido.'];
                }
            } else {
                return $validarDatos;
            }
        } else {
            return $verificar;
        }
    }

    protected function traerDatosPadresSuegrosSocio()
    {
        $PS = DB::table('padres_suegros_socio')
            ->select([
                'relacion_socio_id',
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

        if (!$PS->isEmpty()) {
            return ['estado' => 'success', 'padres_suegros' => $PS];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes datos ingresados.'];
        }
    }

    protected function verificarRelacionIngresada($relacion_socio_id)
    {
        $relacion = DB::table('padres_suegros_socio as pss')
            ->select([
                'rc.descripcion'
            ])
            ->join('relacion_socio as rc', 'rc.id', 'pss.relacion_socio_id')
            ->where([
                'pss.activo' => 'S',
                'pss.socio_id' => $this->socioLogeado()->id,
                'pss.relacion_socio_id' => $relacion_socio_id
            ])
            ->get();

        if (!$relacion->isEmpty()) {
            return ['estado' => 'success', 'relacion' => $relacion];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes datos ingresados.'];
        }
    }

    protected function ingresarDatosPadresSuegrosSocio($request)
    {
        $verificar = $this->verificarSocio($this->socioLogeado()->id);
        if ($verificar['estado'] == 'success') {
            $validarDatos = $this->validarDatos($request, 7);
            if ($validarDatos['estado'] == 'success') {
                $validarRut = $this->validarRut($request->rut);
                if ($validarRut == true) {
                    $tienesDatos = $this->verificarRelacionIngresada($request->relacion_socio_id);
                    if ($tienesDatos['estado'] == 'failed') {
                        $PS = new SocioPadresSuegros;
                        $PS->socio_id = $this->socioLogeado()->id;
                        $PS->relacion_socio_id = $request->relacion_socio_id;
                        $PS->rut = $request->rut;
                        $PS->fecha_nacimiento = $request->fecha_nacimiento;
                        $PS->nombres = $request->nombres;
                        $PS->apellido_paterno = $request->apellido_paterno;
                        $PS->apellido_materno = $request->apellido_materno;
                        $PS->direccion = $request->direccion;
                        $PS->celular = $request->celular;
                        $PS->activo = 'S';
                        if ($PS->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Datos Ingresados Correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Ya tienes ingresado a un(a) ' . $tienesDatos['relacion'][0]->descripcion . ', si deseas modificarlo dirigete al sindicato.'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'El rut ingresado no es valido.'];
                }
            } else {
                return $validarDatos;
            }
        } else {
            return $verificar;
        }
    }

    //--------------------------------------------------------------------------------
    protected function traerAnios()
    {
        return DB::table('anio')->orderBy('descripcion', 'desc')->get();
    }
    protected function anioActual()
    {
        $anio = DB::select("select date_part('year',now()) as anio");
        $anio_db = DB::table('anio')->select(['id', 'descripcion'])
            ->where(['activo' => 'S', 'descripcion' => $anio[0]->anio])->first();
        return response()->json($anio_db);
    }

    protected function traerMeses()
    {
        return DB::table('mes')->orderBy('id', 'asc')->get();
    }

    protected function mesActual()
    {
        $mes_actual = DB::select("select date_part('month',now()) as mes");
        $id = $mes_actual[0]->mes;
        return $this->getMes($id);
    }

    protected function getMes($id)
    {
        switch ($id) {
            case '1':
                $mes = ['id' => $id, 'descripcion' => 'Enero'];
                break;
            case '2':
                $mes = ['id' => $id, 'descripcion' => 'Febrero'];
                break;
            case '3':
                $mes = ['id' => $id, 'descripcion' => 'Marzo'];
                break;
            case '4':
                $mes = ['id' => $id, 'descripcion' => 'Abril'];
                break;
            case '5':
                $mes = ['id' => $id, 'descripcion' => 'Mayo'];
                break;
            case '6':
                $mes = ['id' => $id, 'descripcion' => 'Junio'];
                break;
            case '7':
                $mes = ['id' => $id, 'descripcion' => 'Julio'];
                break;
            case '8':
                $mes = ['id' => $id, 'descripcion' => 'Agosto'];
                break;
            case '9':
                $mes = ['id' => $id, 'descripcion' => 'Septiembre'];
                break;
            case '10':
                $mes = ['id' => $id, 'descripcion' => 'Octubre'];
                break;
            case '11':
                $mes = ['id' => $id, 'descripcion' => 'Noviembre'];
                break;
            case '12':
                $mes = ['id' => $id, 'descripcion' => 'Diciembre'];
                break;
        }

        return $mes;
    }
    //--------------------------------------------------------------------------------

    protected function crearUsuarioSocio($request)
    {
        $traerSocio = PortalSocio::find($request->id);

        if (!is_null($traerSocio)) {
            if (is_null($traerSocio->fecha_egreso)) {
                $user = User::where('rut', $traerSocio->rut)->first();
                if (is_null($user)) {
                    $crear = new User;
                    // $crear->id = User::max('id') + 1;
                    $crear->name = $traerSocio->nombres;
                    $crear->nombres = $traerSocio->nombres;
                    $crear->a_paterno = $traerSocio->a_paterno;
                    $crear->a_materno = $traerSocio->a_materno;
                    $crear->email = 'correo' . rand(1, 999) . '-' . rand(1, 999) . '@prueba.cl';
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
                    //verificar si rol es 5 dar mensaje de que ya tiene acceso mixto sino hacer lo de abajo 
                    if ($user->rol == 5) {
                        return ['estado' => 'failed', 'mensaje' => 'El socio ya tiene sus Credenciales de acceso mixto creadas.'];
                    } else {
                        if ($user->rol == 1) {
                            $user->rol = '5';
                            if ($user->save()) {
                                return ['estado' => 'success', 'mensaje' => 'Credenciales de acceso mixto creadas correctamente.'];
                            } else {
                                return ['estado' => 'failed', 'mensaje' => 'Error al crear las credenciales.'];
                            }
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'El socio no es administrador, si necesitas crear credenciales para el, contactenos.'];
                        }
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
            return ['estado' => 'failed', 'mensaje' => 'Socio no encontrado.'];
        }
    }

    //--------------------------------------------------------------------------------
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
    //--------------------------------------------------------------------------------

}
