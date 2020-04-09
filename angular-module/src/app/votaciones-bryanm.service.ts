import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../app/servicios/global';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class VotacionesBryanmService {
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) {
    this.url = global.url;
  }

  //----------------------------->Funciones Admin

  traerDatos(): Observable<any> {
    return this._http.get(this.url + "traer_datos_archivador", {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'application/json'
        }
      )
    })
  }

  ingresarTema(data): Observable<any> {
    const datos = new FormData();
    datos.append('fecha', data.fecha);
    datos.append('titulo', data.titulo);
    datos.append('descripcion', data.descripcion);
    return this._http.post(this.url + "ingresar_tema", datos, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token
        }
      )
    });
  }

  actualizarTema(data): Observable<any> {
    const datos = new FormData();
    datos.append('id', data.id);
    datos.append('fecha', data.fecha);
    datos.append('titulo', data.titulo);
    datos.append('descripcion', data.descripcion);
    return this._http.post(this.url + "actualizar_tema", datos, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token
        }
      )
    });
  }

  cancelarTema(data): Observable<any> {
    const datos = new FormData();
    datos.append('tema', data);
    return this._http.post(this.url + "cancelar_tema", datos, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token
        }
      )
    });
  }

  traerTemasActivos(): Observable<any> {
    return this._http.get(this.url + "traer_temas_activos", {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'application/json'
        }
      )
    })
  }

  traerTipos(): Observable<any> {
    return this._http.get(this.url + "traer_tipos_temas", {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'application/json'
        }
      )
    })
  }

  traerHistorialTemas(anio, mes, tipo): Observable<any> {
    return this._http.get(this.url + "traer_temas/" + anio + "/" + mes + "/" + tipo, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'application/json'
        }
      )
    })
  }

  //----------------------------->Funciones Admin

}
