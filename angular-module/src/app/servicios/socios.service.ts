import { Injectable } from '@angular/core';

import { HttpClient, HttpHeaders, } from '@angular/common/http';
import { Observable } from 'rxjs';
import { global } from './global';

@Injectable({
  providedIn: 'root'
})
export class SociosService {
  
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) { 
  		this.url = global.url;
  }

  	getTablaSocios(): Observable<any>{
        return this._http.get(this.url + "listar_socios", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    getTablaFilter(search:string): Observable<any>{
    	return this._http.get(this.url + "filtrar_socios/"+search, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
}
