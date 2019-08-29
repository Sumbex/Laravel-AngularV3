import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class BienestarService {

  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) { 
    this.url = global.url;
  }

  guardar_iniciomensual(anio: string, mes: string, cierre_mensual: string): Observable<any> {

      const body = new FormData();
      body.append('anio', anio);
      body.append('mes', mes);
    body.append('cierre_mensual', cierre_mensual);

    return this._http.post(this.url + "cbe_guardar_inicio_mensual", body, {
        headers: new HttpHeaders(
          {
            'Authorization': 'Bearer' + this.token,
            //'Content-Type': 'applcation/json'
          }
        )
      });
  }

  // guardar_iniciomensual(){
  //   // return http.get('cbe_guardar_inicio_mensual');
  // }
}
