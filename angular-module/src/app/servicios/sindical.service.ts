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

    
    getTablaSindicalMontoInicial(anio: string, mes: string): Observable<any>{
        return this._http.get(this.url + "traer_monto_inicial_cs/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    getTablaSindicalActualizar(id,campo,input): Observable<any>{

        const body = new FormData();
        body.append('id', id);
        body.append('campo', campo);
        body.append('input', input);
        console.log(body);

        return this._http.post(this.url + "actualizar_dato_cs",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                //'Content-Type': 'applcation/json'
            }
        )});
    }

    getCalcularCajaChica(anio: string, mes: string): Observable<any>{
        return this._http.get(this.url + "calcular_caja_chica_anterior_cs/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    getTipoPrestamo(){
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get<any>(this.url + "traer_tipo_prestamo", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }

}