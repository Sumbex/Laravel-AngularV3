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

    setDatosSocios(form): Observable<any>{
        const body = new FormData();
        body.append('detalle_prestamo_id', form);
        body.append('fecha', form);
        body.append('monto', form);

        return this._http.post(this.url + "modificar_datos_basicos_socio",body, {headers: new HttpHeaders(
            {
                //'Authorization': 'Bearer' + this.token
            }
        )});
    }
}