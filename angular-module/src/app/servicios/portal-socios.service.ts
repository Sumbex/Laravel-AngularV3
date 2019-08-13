import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable()
export class PortalSociosService{
    public url: string;
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

    getDatosSocios(){
        return this._http.get(this.url + "test", {headers: new HttpHeaders(
            {
                //'Authorization': 'Bearer' + this.token,
                'Content-Type': 'application/json'
            }
        )});
    }

    setDatosSocios(form): Observable<any>{
        const body = new FormData();
        body.append('detalle_prestamo_id', form);
        body.append('fecha', form);
        body.append('monto', form);

        return this._http.post(this.url + "ingresar_pago_prestamo",body, {headers: new HttpHeaders(
            {
                //'Authorization': 'Bearer' + this.token
            }
        )});
    }
}