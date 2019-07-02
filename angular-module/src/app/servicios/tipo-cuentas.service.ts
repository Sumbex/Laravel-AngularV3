import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { global } from './global';

@Injectable()
export class TipoCuentasService{
    public url: string;
    token = localStorage.getItem('token').replace(/['"]+/g, '');

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    getDefinicion(){
        return this._http.get(this.url + "listar_definicion", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'application/json'
            }
        )});
    }

    getTipoCuenta(){
        return this._http.get(this.url + "listar_tipo_cuenta_sindicato", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
}