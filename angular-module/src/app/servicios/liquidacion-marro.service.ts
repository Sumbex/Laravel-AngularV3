import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, } from '@angular/common/http';
import { Observable } from 'rxjs';
import { global } from './global';

@Injectable({
  providedIn: 'root'
})
export class LiquidacionMarroService {

  public url: string;
    token = localStorage.getItem('token').replace(/['"]+/g, '');

    constructor(public _http: HttpClient) {
        this.url = global.url;
    }

    getLiquidacionesEmpleado(idEmpleado): Observable<any> {
      return this._http.get(this.url + "getLiquidacionesPorId/" + idEmpleado, {
          headers: new HttpHeaders(
              {
                  'Authorization': 'Bearer' + this.token,
                  'Content-Type': 'applcation/json'
              }
          )
      });
  }

  setDatosliquidacion(id, formulario): Observable<any>{
    let token = localStorage.getItem('token').replace(/['"]+/g, '');
    const body = new FormData();
    body.append('id', id);
    body.append('fecha', formulario.fecha);
    body.append('archivo', formulario.archivo);
    return this._http.post(this.url + "setDatosLiquidacion", body, {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + token
        }
    )});
}

actualizarLiquidacion(id, campo, input): Observable<any>{
    let token = localStorage.getItem('token').replace(/['"]+/g, '');
    const body = new FormData();
    body.append('id', id);
    body.append('campo', campo);
    body.append('input', input);
    return this._http.post(this.url + "actualizarLiquidacion", body, {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + token
        }
    )});
}

}
