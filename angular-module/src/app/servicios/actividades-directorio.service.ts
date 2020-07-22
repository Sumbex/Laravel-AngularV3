import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';
import { Actividades } from '../modelos/actividadesDirecctorio.model';

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

  traerActividadesActivas(): Observable<any> {
    return this._http.get(this.url + "traer_actividades_activas", {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'application/json'
        }
      )
    })
  }

}
