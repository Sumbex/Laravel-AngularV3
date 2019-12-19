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

  ingresarNoticia(form): Observable<any> {
    console.log(form);
    let token = localStorage.getItem('token').replace(/['"]+/g, '');
    const body = new FormData();
    body.append('titulo', form.titulo);
    body.append('cuerpo', form.cuerpo);
    body.append('archivo', form.foto);

    return this._http.post(this.url + "crear_noticia_sec", body, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + token
        }
      )
    });
  }
}
