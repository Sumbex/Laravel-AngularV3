import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';


@Injectable({
    providedIn: 'root'
  })
  
export class ConsorcioService {

    
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) { 
    this.url = global.url;
  }


  listar_consorcio():Observable<any>{
    return this._http.get(this.url + "traer_socios_consorcios", {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'applcation/json'
        }
      )
    });
  }
  
}