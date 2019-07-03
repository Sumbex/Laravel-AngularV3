import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { global } from './global'; 

@Injectable()
export class AniosService{
    public url: string;

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    getAnios(){
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "listar_anios", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }

    getMeses(){
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "listar_meses", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }

    getAnioActual(){
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "anio_actual", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }
    
    getMesActual(){
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "mes_actual", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }

}