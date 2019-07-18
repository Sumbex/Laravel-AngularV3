import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, } from '@angular/common/http';
import { Observable } from 'rxjs';
import { global } from './global';

@Injectable({
  providedIn: 'root'
})
export class ValidarUsuarioService {

   public url: string;
   token = localStorage.getItem('token').replace(/['"]+/g, '');
   
	constructor(public _http: HttpClient) { 

	  	this.url = global.url;
	}


	usuario_logeado(): Observable<any>{
      
      return this._http.get(this.url + "usuario_logeado",{headers: new HttpHeaders(
                {'Authorization': 'Bearer' + this.token})}
        )
    }

    validar_usuario(formData): Observable<any>{

    	return this._http.post(this.url + "confirmar_usuario", formData,{headers: new HttpHeaders(
                {'Authorization': 'Bearer' + this.token})}
        )
    }
}
