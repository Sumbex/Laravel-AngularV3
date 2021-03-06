import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable()
export class BryanBienestarService{
    public url: string;

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    //Servicio para llamar a la caja chica
    getCajaChicaBienestar(anio: string, mes: string): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_caja_chica_bienestar/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    //Servicio para llamar a la caja chica
    getCajaChicaBienestarSocios(anio: string, mes: string): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_caja_chica_bienestar_socio/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    //Servicio para llamar a la Cuenta de Bienestar
    getCuentaBienestar(anio: string, mes: string): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_cuenta_bienestar_socio/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    //*********************INGRESAR A CAJA CHICA*************************/
    setCajaChicaBienestar(form) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('fecha', form.fecha);
        body.append('archivo_documento', form.archivo_documento);
        body.append('numero_documento', form.numero_documento);
        body.append('descripcion', form.descripcion);
        body.append('monto', form.monto);
        body.append('definicion', form.definicion);
        
        return this._http.post(this.url + "ingresar_caja_chica_bienestar",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    //*********************ACTUALIZAR CAMPO EN CAJA CHICA*************************/
    updateCampoCajaChica(valor, campo, input) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('id', valor);
        body.append('campo', campo);
        body.append('input', input);

        return this._http.post(this.url + "modificar_caja_chica_bienestar",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    /**************************TRAER FONDOS MUTUTOS SOCIOS*****************************/

    getAhorrosSocio(anio: string): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_mis_ahorros_socio/" + anio, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

}