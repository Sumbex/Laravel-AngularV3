import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';
import { Actividades } from '../modelos/actividadesDirecctorio.model';
import { Anios } from '../modelos/anios.model';
import { Meses } from '../modelos/meses.model';

@Injectable({
  providedIn: 'root'
})
export class ActividadesDirectorioService {
  public url: string;
  public token: string = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) {
    this.url = global.url;
  }

  ingresarActividad(actividad: Actividades): Observable<any> {
    return this._http.post(this.url + "ingresar_actividad", actividad, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token
        }
      )
    });
  }

  traerActividades(anio: Anios, mes: Meses): Observable<any> {
    return this._http.get(this.url + "traer_actividades/" + anio + '/' + mes, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'application/json'
        }
      )
    })
  }

  traerTiposAct(): Observable<any> {
    return this._http.get(this.url + "traer_tipos_actividades", {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'application/json'
        }
      )
    })
  }
}
