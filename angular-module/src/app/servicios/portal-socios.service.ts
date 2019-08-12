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