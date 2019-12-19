import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AcuerdoAsambleaService {
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient){
      this.url = global.url;
  }

  setAcuerdoAsamblea(form): Observable<any>{
    
    const body = new FormData();
    body.append('titulo', form.tituloActa);
    body.append('fecha', form.fechaActa);
    body.append('contenido', form.contenidoActa);
    body.append('idTipoAcuerdo', form.tipoActa);
    body.append('idEstadoAcuerdo', form.estadoActa);

    return this._http.post(this.url + "set_acuerdo", body, {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + this.token
        }
    )});
}

getAcuerdosAsambleaMesAnio(anio): Observable<any>{
    return this._http.get(this.url + "listarAcuerdosAsamblea/" + anio, {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + this.token,
            'Content-Type': 'applcation/json'
        }
    )});
}

getAcuerdosAsamblea(): Observable<any>{
  return this._http.get(this.url + "get_acuerdos", {headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'applcation/json'
      }
  )});
}

}
