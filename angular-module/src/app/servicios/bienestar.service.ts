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

  // guardar_iniciomensual(){
  //   // return http.get('cbe_guardar_inicio_mensual');
  // }

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
  return this._http.get(this.url + "listar_c_b/" + anio + "/" + mes, {headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'applcation/json'
      }
  )});
}


// getTablaSindicalMontoInicial(anio: string, mes: string): Observable<any>{
//   return this._http.get(this.url + "traer_monto_inicial_cs/" + anio + "/" + mes, {headers: new HttpHeaders(
//       {
//           'Authorization': 'Bearer' + this.token,
//           'Content-Type': 'applcation/json'
//       }
//   )});
// }

getTablaBienestarActualizar(id,campo,input): Observable<any>{

  const body = new FormData();
  body.append('id', id);
  body.append('campo', campo);
  body.append('input', input);

  return this._http.post(this.url + "actualizar_dato_cb",body, {headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          //'Content-Type': 'applcation/json'
      }
  )});
}

// getCalcularCajaChica(anio: string, mes: string): Observable<any>{
//   return this._http.get(this.url + "calcular_caja_chica_anterior_cs/" + anio + "/" + mes, {headers: new HttpHeaders(
//       {
//           'Authorization': 'Bearer' + this.token,
//           'Content-Type': 'applcation/json'
//       }
//   )});
// }

// getCalcularCajaChicaActualizar(anio: string, mes: string): Observable<any>{
//   return this._http.get(this.url + "calcular_caja_chica_anterior_cs2/" + anio + "/" + mes, {headers: new HttpHeaders(
//       {
//           'Authorization': 'Bearer' + this.token,
//           'Content-Type': 'applcation/json'
//       }
//   )});
// }







}
