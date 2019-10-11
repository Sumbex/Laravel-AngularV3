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
        body.append('fecha_inicio', form.fecha);
        body.append('tipo', form.tipoReunion);
        body.append('cabeza', form.titulo);
        body.append('cuerpo', form.descripcion);
        
        return this._http.post(this.url + "crear_reunion_sec",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    /**************************TRAER REUNION ACTIVA*****************************/

    getReunionActiva(): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_reunion_activa_sec", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    //*********************ACTUALIZAR REUNION************************/
    updateNuevaReunion(form) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('id', form.id);
        body.append('fecha_inicio', form.fecha);
        body.append('tipo', form.tipoReunion);
        body.append('cabeza', form.titulo);
        body.append('cuerpo', form.descripcion);
        
        return this._http.post(this.url + "modificar_reunion_activa_sec",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    //*********************CANCELAR REUNION************************/
    cancelarReunion(form) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('id', form.id);
        
        return this._http.post(this.url + "cancelar_reunion_activa_sec",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    //*********************TERMINAR REUNION************************/
    terminarReunion(form) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('id', form.id);
        
        return this._http.post(this.url + "terminar_reunion_activa_sec",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    /**************************TRAER USUARIO*****************************/

    getUsuario(rut): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_socio_por_rut_sec/" + rut, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    //*********************MARCAR ASISTENCIA************************/
    marcarAsistenciaUsuario(socio_id, reunion_id) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('id', socio_id);
        body.append('id', reunion_id);
        
        return this._http.post(this.url + "marcar_asistencia_socio_sec",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    /**************************TRAER REUNION CERRADA FINALIZADA*****************************/

    getReunionCerradaFinalizada(): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_reunion_cf_sec", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    /**************************TRAER SOLO LISTA DE ASISTENTES*****************************/

    getListaAsistentes(): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_lista_asistentes_cf_sec", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    /**************************TRAER SOLO LISTA DE ASISTENCIA COMPLETA*****************************/

    getListaAsistenciaCompleta(): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_lista_asistencia_completa_cf_sec", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
}