import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable()
export class CajaChicaService{
    public url: string;
    token = localStorage.getItem('token').replace(/['"]+/g, '');

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    ingresarValor(form): Observable<any>{
        console.log(form.definicion);

        return this._http.post(this.url + "ingresar_caja_chica?", 'fecha=' + form.fecha + '&' + 'numero_documento=' + form.numero_documento + '&' + 'descripcion=' + form.descripcion + '&' + 'monto=' + form.monto_egreso + '&' + 'definicion=' + form.definicion, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        )});
    }

}