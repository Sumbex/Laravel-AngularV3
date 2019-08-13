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

    getEditar(form): Observable<any>{
      

      return this._http.post(this.url + "editar_socios", form, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                //'Content-Type': 'applcation/json'
            }
            )});
        }

    getSocio(rut){
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get<any>(this.url + "traer_socio/" + rut, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }

    traerDatosSocio(rut){
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get<any>(this.url + "buscar_socio_por_rut/"+rut, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }

    insertarDatosSocio(form): Observable<any>{
      

        return this._http.post(this.url + "guardar_datos_socio", form, {headers: new HttpHeaders(
              {
                  'Authorization': 'Bearer' + this.token,
              }
              )});
          }

          getDatosSocio(idSocio){
            let token = localStorage.getItem('token').replace(/['"]+/g, '');
            return this._http.get<any>(this.url + "traer_datos_socio/"+idSocio, {headers: new HttpHeaders(
                {
                    'Authorization': 'Bearer' + token,
                    'Content-Type': 'application/json'
                }
            )});
          }

          insertarDatosConyuge(form): Observable<any>{

            return this._http.post(this.url + "guardar_datos_conyuge", form, {headers: new HttpHeaders(
                    {
                        'Authorization': 'Bearer' + this.token,
                    }
                    )});
                }

            getDatosConyuge(idSocio){
            let token = localStorage.getItem('token').replace(/['"]+/g, '');
            return this._http.get<any>(this.url + "traer_datos_conyuge/"+idSocio, {headers: new HttpHeaders(
                {
                    'Authorization': 'Bearer' + token,
                    'Content-Type': 'application/json'
                }
            )});
            }

            insertarDatosBeneficiario(form): Observable<any>{

                return this._http.post(this.url + "guardar_datos_beneficiario", form, {headers: new HttpHeaders(
                        {
                            'Authorization': 'Bearer' + this.token,
                        }
                        )});
                    }
    
                getDatosBeneficiario(idSocio){
                let token = localStorage.getItem('token').replace(/['"]+/g, '');
                return this._http.get<any>(this.url + "traer_datos_beneficiario/"+idSocio, {headers: new HttpHeaders(
                    {
                        'Authorization': 'Bearer' + token,
                        'Content-Type': 'application/json'
                    }
                )});
                }
                
                insertarDatosCarga(form): Observable<any>{

                    return this._http.post(this.url + "guardar_datos_carga", form, {headers: new HttpHeaders(
                            {
                                'Authorization': 'Bearer' + this.token,
                            }
                            )});
                        }
        
                    getDatosCarga(idSocio){
                    let token = localStorage.getItem('token').replace(/['"]+/g, '');
                    return this._http.get<any>(this.url + "traer_datos_carga/"+idSocio, {headers: new HttpHeaders(
                        {
                            'Authorization': 'Bearer' + token,
                            'Content-Type': 'application/json'
                        }
                    )});
                    }

                    insertarDatosPS(form): Observable<any>{

                        return this._http.post(this.url + "guardar_datos_padres_suegros", form, {headers: new HttpHeaders(
                                {
                                    'Authorization': 'Bearer' + this.token,
                                }
                                )});
                            }
            
                        getDatosPS(idSocio){
                        let token = localStorage.getItem('token').replace(/['"]+/g, '');
                        return this._http.get<any>(this.url + "traer_datos_padres_suegros/"+idSocio, {headers: new HttpHeaders(
                            {
                                'Authorization': 'Bearer' + token,
                                'Content-Type': 'application/json'
                            }
                        )});
                        }

                        ActualizarDatosSocio(form): Observable<any>{

                            return this._http.post(this.url + "actualizar_datos_socio", form, {headers: new HttpHeaders(
                                    {
                                        'Authorization': 'Bearer' + this.token,
                                    }
                                    )});
                                }

          
  
}
