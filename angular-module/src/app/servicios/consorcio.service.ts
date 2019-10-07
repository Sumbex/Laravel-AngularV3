import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';


@Injectable({
    providedIn: 'root'
  })
  
export class ConsorcioService {

    
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) { 
    this.url = global.url;
  }


  listar_consorcio():Observable<any>{
    return this._http.get(this.url + "traer_socios_consorcios", {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'applcation/json'
        }
      )
    });
  }

  insertar_consorcio(form): Observable<any> {

    return this._http.post(this.url + "insertar_consorcio", form, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )
    });
  }


  getTablaFilter(buscar): Observable<any> {

    return this._http.get(this.url + "filtrar_socios_consorcios/" + buscar, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )
    });
  }

  getTablaConsorcio(anio,buscar): Observable<any> {

    return this._http.get(this.url + "filtrar_tabla_consorcio/" + anio +"/"+ buscar, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )
    });
  }

  getTablaConsorcios(anio): Observable<any> {

    return this._http.get(this.url + "cc_listar/" + anio, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )
    });
  }

  getTablaConsorciosTotalesMensuales(anio): Observable<any> {

    return this._http.get(this.url + "totales_cc/" + anio, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )
    });
  }

  getTablaConsorciosTotalAnual(anio): Observable<any> {

    return this._http.get(this.url + "total_anual_socio/" + anio, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )
    });
  }

  getTablaConsorciosDesvinculados(anio): Observable<any> {

    return this._http.get(this.url + "tabla_desvinculados/" + anio, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )
    });
  }
  calcularPagoBeneficio(idSocio,tipo,porcentaje): Observable<any> {

    return this._http.get(this.url + "calcular_dia_sueldo/" + idSocio +"/"+ tipo +"/"+ porcentaje, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
            }
        )
    });
  }

}