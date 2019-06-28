import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable()
export class SindicalService{
    public url: string;

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    ingresarValor(form): Observable<any>{
        let json = JSON.stringify(form);
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')
        return this._http.post(this.url + 'guardar_item_c_s?', "fecha=" + form.fecha.year + "-" + form.fecha.month + "&" + "n_documento=" + form.nDocumento + "&" + "tipo_cuenta_sindicato=" + form.tipoCuentaSindicato + "&" + "descripcion=" + form.descripcion + "&" + "definicion=" + form.definicion + "&" + "monto=" + form.monto, { headers : headers });
    }

}