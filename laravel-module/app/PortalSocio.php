<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        $verificar = $this->verificarSocio($request->socio_id);

        if ($verificar['estado'] == 'success') {
            $socio = PortalSocio::find($request->socio_id);
            switch ($request->nombre_campo) {
                case 'nombres':
                    $socio->nombres = $request->input;
                    if ($socio->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Nombres Actualizados.'];
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
    }
}
