import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class LeyesService {
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) {
    this.url = global.url;
  }

  // administracion
  ingresarNoticia(form): Observable<any> {
    console.log(form);
    let token = localStorage.getItem('token').replace(/['"]+/g, '');
    const body = new FormData();
    body.append('titulo', form.titulo);
    body.append('cuerpo', form.cuerpo);
    body.append('archivo', form.foto);
    body.append('estado', form.estado);

    return this._http.post(this.url + "crear_noticia_sec", body, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + token
        }
      )
    });
  }

  //portal socios

  traerNoticiasSocio(pagina): Observable<any> {
    let token = localStorage.getItem('token').replace(/['"]+/g, '');
    if (pagina == null) {
      return this._http.get(this.url + "traer_noticias_sec_socios", {
        headers: new HttpHeaders(
          {
            'Authorization': 'Bearer' + token,
            'Content-Type': 'applcation/json'
          }
        )
      });
    } else {
      return this._http.get(this.url + "traer_noticias_sec_socios?page="+ pagina, {
        headers: new HttpHeaders(
          {
            'Authorization': 'Bearer' + token,
            'Content-Type': 'applcation/json'
          }
        )
      });
    }
  }
}
