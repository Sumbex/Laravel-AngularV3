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
        body.append('fecha', form.fecha);
        body.append('n_documento', form.nDocumento);
        body.append('archivo', form.archivoDocumento);
        body.append('tipo_cuenta_sindicato', form.tipoCuentaSindicato);
        body.append('descripcion', form.descripcion);
        body.append('definicion', form.definicion);
        body.append('monto', form.monto);
        
        console.log(body);

        return this._http.post(this.url + "guardar_item_c_s", body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token
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