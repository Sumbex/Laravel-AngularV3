import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
  })
export class BryanConsorcioService{

  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) { 
    this.url = global.url;
  }

  getConsorcio(anio): Observable<any>{
    return this._http.get(this.url + "traer_cuenta_consorcio_socio/" + anio, {
        headers: new HttpHeaders(
          {
            'Authorization': 'Bearer' + this.token,
            'Content-Type': 'applcation/json'
          }
        )
      });
  }

}