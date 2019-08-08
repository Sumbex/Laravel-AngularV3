<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
/* use Illuminate\Support\Facades\Config; */
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
            if (!$this->validarRut($request->email)) {
                return ['status' => 'failed', 'mensaje' => 'El rut ingresado no es valido.'];
            } else {
                $socio = PortalSocio::where('rut', $request->email)->first();
                /* dd($socio); */

                /* dd(Hash::check($request->password, $socio->password)); */

                if (Hash::check($request->password, $socio->password)) {
                    /* Config */
                    \Config::set('jwt.user', PortalSocio::class);
                    \Config::set('auth.providers.users.model', PortalSocio::class);
                    $credentials = $request->only('email', 'password');

                    /* dd(!$token = JWTAuth::attempt($credentials)); */
                    if (!$token = JWTAuth::attempt($credentials)) {
                        return response([
                            'status' => 'error',
                            'error' => 'invalid.credentials',
                            'msg' => 'Invalid Credentials.'
                        ], 400);
                    }
                    return response([
                        'status' => 'success',
                        'token' => $token
                    ])
                        ->header('Authorization', $token);
                }
                return response(['status' => 'failed', 'mensaje' => 'La contraseña ingresado no es valida.']);
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
        $socio = PortalSocio::find(Auth::user()->id);
        return $socio;
    }

    protected function actualizarDatosSocios($request)
    {
        //
    }
}
