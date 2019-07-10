import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable()
export class SindicalService{
    public url: string;
    token = localStorage.getItem('token').replace(/['"]+/g, '');

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    ingresarValor(form): Observable<any>{
    
        const body = new FormData();
        body.append('fecha', form.fecha.year.value);
        body.append('n_documento', form.nDocumento.value);
        body.append('tipo_cuenta_sindicato', form.tipoCuentaSindicato.value);
        body.append('descripcion', form.fecha.descripcion.value);
        body.append('definicion', form.definicion.value);
        body.append('monto', form.monto.value);
        
        console.log(body);

        return this._http.post(this.url + "guardar_item_c_s", body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )});
    }

    getTablaSindical(anio: string, mes: string): Observable<any>{
        return this._http.get(this.url + "listar_c_s/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

}