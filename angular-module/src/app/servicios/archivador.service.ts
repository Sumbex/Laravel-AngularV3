import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ArchivadorService {
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) {
    this.url = global.url;
  }

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

  ingresarNota(data): Observable<any> {
    const datos = new FormData();
    datos.append('fecha', data.fecha);
    datos.append('descripcion', data.nota);
    return this._http.post(this.url + "ingresar_notas", datos, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token
        }
      )
    });
  }

  traerNotas(anio, mes): Observable<any> {
    return this._http.get(this.url + "traer_notas/" + anio + "/" + mes, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'application/json'
        }
      )
    })
  }

  ingresarArchivo(data): Observable<any> {
    const datos = new FormData();
    datos.append('fecha', data.fecha);
    datos.append('titulo', data.titulo);
    datos.append('tipo', data.tipo);
    datos.append('archivo', data.archivo);
    return this._http.post(this.url + "ingresar_archivos", datos, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token
        }
      )
    });
  }

  traerArchivos(anio, mes): Observable<any> {
    return this._http.get(this.url + "traer_archivos/" + anio + "/" + mes, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'application/json'
        }
      )
    })
  }

  traerTipos(): Observable<any> {
    return this._http.get(this.url + "traer_tipos_archivador", {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'application/json'
        }
      )
    })
  }

}
