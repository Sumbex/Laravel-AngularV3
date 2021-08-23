import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DocuemntacionSindicalService {
  public url: string;
    token = localStorage.getItem('token').replace(/['"]+/g, '');

    head = {
      'Authorization': 'Bearer' + this.token,
      'Content-Type': 'applcation/json'
    }
    constructor(public _http: HttpClient){
        this.url = global.url;
    }
    registro(form): Observable<any>{
      return this._http.post(this.url + "documentacion_sindical/insert", form, {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + this.token,
        }
    )});
    }

    listado(tipo:string): Observable<any>{
      return this._http.get(this.url + "documentacion_sindical/list/"+tipo+"", {headers: new HttpHeaders(
           this.head
        )});
    }

    suspender(doc:string): Observable<any>{
      return this._http.get(this.url + "documentacion_sindical/suspender/"+doc, {headers: new HttpHeaders(
           this.head
        )});
    }

    listado_tipos():Observable<any>{
      
      return this._http.get(this.url + "documentacion_sindical/tipos/list", {headers: new HttpHeaders(
        this.head
     )});
    }
}
