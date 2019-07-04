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
        let json = JSON.stringify(form);
    
        /*let body = new FormData();
        body.append('fecha', form.fecha.year.value);
        body.append('n_documento', form.nDocumento.value);
        body.append('tipo_cuenta_sindicato', form.tipoCuentaSindicato.value);
        body.append('descripcion', form.fecha.descripcion.value);
        body.append('definicion', form.definicion.value);
        body.append('monto', form.monto.value);
        */
        //console.log('fecha=' + form.fecha.year + '-' + form.fecha.month + '-' + form.fecha.day + '&' + 'n_documento=' + form.nDocumento + '&' + 'tipo_cuenta_sindicato=' + form.tipoCuentaSindicato + '&' + 'descripcion=' + form.descripcion + '&' + 'definicion=' + form.definicion + '&' + 'monto=' + form.monto );
        console.log(form.definicion);

        return this._http.post(this.url + "guardar_item_c_s?", form.fecha + '&' + 'n_documento=' + form.nDocumento + '&' + 'tipo_cuenta_sindicato=' + form.tipoCuentaSindicato + '&' + 'descripcion=' + form.descripcion + '&' + 'definicion=' + form.definicion + '&' + 'monto=' + form.monto , {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        )});
    }

}