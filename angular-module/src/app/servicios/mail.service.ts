import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { global } from './global';

@Injectable({
  providedIn: 'root'
})
export class MailService {

    public url: string;
    token = localStorage.getItem('token').replace(/['"]+/g, '');

    constructor(public _http: HttpClient) {
        this.url = global.url;
    }

  enviar_correo(correo):Observable<any>{
    
    const body = new FormData();
    body.append('correo', correo);
    
    return this._http.post(this.url + "enviar_correo", body, {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + this.token
        }
    )});
  }
}
