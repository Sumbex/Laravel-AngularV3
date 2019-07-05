import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { global } from './global';
import { Definicion } from '../modelos/definicion.model';
import { Detalle } from '../modelos/detalle.model';
import { map } from 'rxjs/operators';

@Injectable()
export class TipoCuentasService{
    public url: string;
    selectDefinicion: Definicion[] = [];
    selectDetalle: Detalle[] = [];

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    getDefinicion(){
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get<any>(this.url + "listar_definicion", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }

    getTipoCuenta(){
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get<any>(this.url + "listar_tipo_cuenta_sindicato", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
}