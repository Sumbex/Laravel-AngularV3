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
        /* dd(Auth::guard('socio_api')->user()); */
        $socio = PortalSocio::find(Auth::guard('socio_api')->user()->id);
        return /* Auth::guard('socio_api')->user() */ $socio;
    }

    protected function actualizarDatosSocios($request)
    {
        //
    }
}
