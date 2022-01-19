import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';
@Injectable({
  providedIn: 'root'
})
export class DirectivasService {

  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  constructor(public _http: HttpClient) {
    this.url = global.url;
   }

   listar_directivas(): Observable<any>{
    return this._http.get(this.url + "traer_directivas");
  }
}
