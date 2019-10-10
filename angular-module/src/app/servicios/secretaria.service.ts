import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable()
export class SecretariaService{
    public url: string;

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    //*********************GENERAR NUEVA REUNION************************/
    setNuevaReunion(form) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('fecha', form.fecha);
        body.append('tipo', form.tipoReunion);
        body.append('titulo', form.titulo);
        body.append('descripcion', form.descripcion);
        
        return this._http.post(this.url + "ingresar_nueva_reunion",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    /**************************TRAER REUNION ACTIVA*****************************/

    getReunionActiva(): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_reunion_activa", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    //*********************Actualizar REUNION************************/
    updateNuevaReunion(form) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('fecha', form.fecha);
        body.append('tipo', form.tipoReunion);
        body.append('titulo', form.titulo);
        body.append('descripcion', form.descripcion);
        
        return this._http.post(this.url + "actualizar_reunion",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    //*********************ACTUALIZAR REUNION************************/
    cancelarReunion(form) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('id', form.id);
        
        return this._http.post(this.url + "cancelar_reunion",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }
}