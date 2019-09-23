import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class BienestarService {

  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) { 
    this.url = global.url;
  }

  guardar_iniciomensual(anio: string, mes: string, cierre_mensual: string): Observable<any> {

      const body = new FormData();
      body.append('anio', anio);
      body.append('mes', mes);
    body.append('cierre_mensual', cierre_mensual);

    return this._http.post(this.url + "cbe_guardar_inicio_mensual", body, {
        headers: new HttpHeaders(
          {
            'Authorization': 'Bearer' + this.token,
            //'Content-Type': 'applcation/json'
          }
        )
      });
  }

  calcular(anio, mes): Observable<any>{

    return this._http.get(this.url + "cbe_calcular_cm/" + anio + '/' + mes, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'applcation/json'
        }
      )
    });
  }

  listar_inicio_cierre(anio):Observable<any>{
    return this._http.get(this.url + "listar_inicio_y_cierre_mensual_cbe/" + anio, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'applcation/json'
        }
      )
    });
  }

insertar_CuentaBienestar(form): Observable<any> {

  return this._http.post(this.url + "cbe_insertar", form, {
      headers: new HttpHeaders(
          {
              'Authorization': 'Bearer' + this.token,
          }
      )
  });
}

getTablaBienestar(anio: string, mes: string): Observable<any>{
  return this._http.get(this.url + "cbe_listar/" + anio + "/" + mes, {headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'applcation/json'
      }
  )});
}

getTablaBienestarActualizar(id,campo,input): Observable<any>{

  const body = new FormData();
  body.append('id', id);
  body.append('campo', campo);
  body.append('input', input);

  return this._http.post(this.url + "actualizar_dato_cbe",body, {headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          //'Content-Type': 'applcation/json'
      }
  )});
}
traer_monto_inicial_cbe(anio, mes): Observable<any>{
  
  return this._http.get(this.url + "traer_monto_inicial_cbe/"+anio+'/'+mes, {
    headers: new HttpHeaders(
      {
        'Authorization': 'Bearer' + this.token,
        'Content-Type': 'applcation/json'
      }
    )
  });

}

calcular_caja_chica_cbe(anio: string, mes: string): Observable<any>{
  return this._http.get(this.url + "calcular_caja_chica_anterior_cbe/" + anio + "/" + mes, {headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'applcation/json'
      }
  )});
}

getCalcularCajaChicaActualizar(anio: string, mes: string): Observable<any>{
  return this._http.get(this.url + "calcular_caja_chica_anterior_cbe2/" + anio + "/" + mes, {headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'applcation/json'
      }
  )});
}


getBeneficiosCobrados(idSocio): Observable<any>{
  return this._http.get(this.url + "listar_beneficios_cobrados/" + idSocio, {headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'applcation/json'
      }
  )});
}

}
