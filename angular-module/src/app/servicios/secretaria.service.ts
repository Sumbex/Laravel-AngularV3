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

    //*********************ARCHIVAR REUNIÓN************************/
    archivarReunion(form) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('id', form.id);
        
        return this._http.post(this.url + "archivar_reunion_sec",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    /**************************TRAER USUARIO*****************************/

    getUsuario(rut, reunion): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_socio_por_rut_sec/" + rut + "/" + reunion, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    /**************************TRAER JUSTIFICACION DEL USUARIO*****************************/

    getJustificacionUsuario(idReunion, idSocio): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_justificacion_socio_sec/" + idReunion + "/" + idSocio, {headers: new HttpHeaders(
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
        body.append('socio_id', socio_id);
        body.append('reunion_id', reunion_id);
        
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

    getListaAsistentes(id): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_socios_presentes_sec/" + id, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    /**************************TRAER SOLO LISTA DE ASISTENCIA COMPLETA*****************************/

    getListaAsistenciaCompleta(id): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_lista_reunion_sec/" + id, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    /* **************************TRAER HISTORIAL DE REUNIONES*********************** */

    getListaReuniones(): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_lista_reuniones", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
}