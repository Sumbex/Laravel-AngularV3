import { Injectable } from '@angular/core';

import { HttpClient, HttpHeaders, } from '@angular/common/http';
import { Observable } from 'rxjs';
import { global } from './global';

@Injectable({
  providedIn: 'root'
})
export class CsDetalleCampingService {

	public url: string;
    token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) {

  	this.url = global.url;
  }

  	getLista(anio:string, mes:string): Observable<any>{
    	return this._http.get(this.url + "listar_detalle_camping/"+anio+"/"+mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    guardar(anio:string, mes:string, monto:string): Observable<any>{
    	let data = new FormData();
    	data.append('anio',anio);
    	data.append('mes',mes);
    	data.append('monto',monto);

    	return this._http.post(this.url + "guardar_cierre_monto_detalle_camping_cs",data, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                //'Content-Type': 'applcation/json'
            }
        )});
    }

    getMonto(anio:string, mes:string): Observable<any>{
    	return this._http.get(this.url + "traer_cierre_monto_detalle_camping_cs/"+anio+'/'+mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
}
