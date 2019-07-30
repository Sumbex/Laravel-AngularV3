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

    ingresarValor(datos): Observable<any>{

        console.log(datos);
        const form = new FormData();
        form.append('fecha', datos.fecha);
        form.append('archivo_documento', datos.archivo_documento);
        form.append('numero_documento', datos.numero_documento);
        form.append('descripcion', datos.descripcion);
        form.append('monto', datos.monto_egreso);
        form.append('definicion', datos.definicion);

        return this._http.post(this.url + "ingresar_caja_chica", form, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )});
    }

    modificarValor(id, campo, nuevoValor): Observable<any>{
        console.log(nuevoValor);
        const form = new FormData();
        form.append('id', id);
        form.append('campo', campo);
        form.append('input', nuevoValor);

        return this._http.post(this.url + "modificar_caja_chica", form, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )});
    }

    getCajaChica(anio: string, mes: string): Observable<any>{
        return this._http.get(this.url + "traer_caja_chica/" + anio + '/' + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    getTotalesCajaChica(anio: string, mes:string): Observable<any>{
        return this._http.get(this.url + "totales_caja_chica/" + anio + '/' + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

}