import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable()
export class ReunionesService{

    public url : string;

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    //*********************INSERTAR DATOS DE JUSTIFICACION*************************/
    setDatosJustificacion(form) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('input', form.mensaje);
        body.append('nombre_campo', form.motivo);

        return this._http.post(this.url + "ingresar_justificacion",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    /*********************************OBTENER LA REUNION ACTIVA***************************************/
    getReunionActiva(): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_reunion_bienestar/", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    /*********************************OBTENER LA REUNION INACTIVA***************************************/
    getReunionInactiva(): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_reunion_inactiva/", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
}