import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DirectorioService {
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient){
      this.url = global.url;
  }

  registroDirectorio(datos): Observable<any>{
    const form = new FormData();
    form.append('fecha', datos.fecha);
    form.append('sec_tipo_reunion_id', datos.sec_tipo_reunion_id);
    form.append('titulo', datos.titulo);
    form.append('asistencia', datos.asistencia);
    form.append('temas', datos.temas);
    form.append('presidente', datos.presidente);
    form.append('secretario', datos.secretario);
    form.append('tesorero', datos.tesorero);
    form.append('primerDirector', datos.primerDirector);
    form.append('segundoDirector', datos.segundoDirector);
    form.append('acuerdos', datos.acuerdos);

    return this._http.post(this.url + "registro_directorio", form, {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + this.token,
        }
    )});
}


visualizarDirectorio(): Observable<any>{
  let token = localStorage.getItem('token').replace(/['"]+/g, '');
  return this._http.get(this.url + "visualizar_directorio", {headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + token,
          'Content-Type': 'applcation/json'
      }
  )});
}

visualizarDirectorioDetalle(id): Observable<any>{
  let token = localStorage.getItem('token').replace(/['"]+/g, '');
  return this._http.get(this.url + "visualizar_directorio_detalle" +'/'+ id, {headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + token,
          'Content-Type': 'applcation/json'
      }
  )});
}

}
