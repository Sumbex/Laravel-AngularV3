import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable()
export class PortalSociosService{
    public url: string;
    rol = '';
    //token = localStorage.getItem('token').replace(/['"]+/g, '');

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    loginSocios(form) : Observable<any>{
        const body = new FormData();
        body.append('rut',form.email);
        body.append('password', form.password);

        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

        return this._http.post(this.url + "login_socios?", "rut=" + form.email + "&" + "password=" + form.password, {headers: headers});

    }

    getSocioLogeado() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "socio_logeado",{headers: new HttpHeaders(
            {'Authorization': 'Bearer' + token})}
    );
    }

    getDatosSocios() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_datos_basicos_socio", {headers: new HttpHeaders(
            {'Authorization': 'Bearer' + token}
        )});
    }

    setDatosBasicosSocios(form): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('direccion', form.direccion);
        body.append('telefono', form.telefono);
        body.append('celular', form.celular);
        body.append('anexo', form.anexo);
        body.append('email_1', form.email_1);
        body.append('email_2', form.email_2);
        body.append('cargo_planta', form.cargo_planta);
        body.append('cargo_comision_sindicato', form.cargo_comision_sindicato);
        body.append('casa_propia', form.casa_propia);
        body.append('rol_turno', form.rol_turno);
        body.append('estado_civil_id', form.estado_civil_id);
        body.append('conyuge', form.conyuge);
        body.append('numero_cuenta', form.numero_cuenta);
        body.append('tipo_cuenta_banco_id', form.tipo_cuenta_banco_id);
        body.append('banco', form.banco);
        body.append('isapre_fonasa', form.isapre_fonasa);
        body.append('grupo_sangre', form.grupo_sangre);

        console.log(form);

        return this._http.post(this.url + "ingresar_datos_basicos_socio",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    setDatosConyugeSocio(form): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('rut', form.rut);
        body.append('fecha_nacimiento', form.fechaNacimiento);
        body.append('nombres', form.nombres);
        body.append('apellido_paterno', form.apellidoPaterno);
        body.append('apellido_materno', form.apellidoMaterno);
        body.append('direccion', form.direccion);
        body.append('celular', form.celular);

        return this._http.post(this.url + "ingresar_datos_conyuge_socio",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    getDatosConyuge() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_datos_conyuge_socio", {headers: new HttpHeaders(
            {'Authorization': 'Bearer' + token}
        )});
    }
}