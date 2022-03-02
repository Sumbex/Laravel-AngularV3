import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Router } from "@angular/router";
import { JwtHelperService } from "@auth0/angular-jwt";
import { Observable, of } from "rxjs";
import { map } from "rxjs/operators";
import { global } from "./global";

@Injectable({
  providedIn: "root",
})
export class VacacionesService {
  public url: string;
  token = "";
  rol = "";
  public usuario: string;
  header: HttpHeaders = new HttpHeaders({
    Authorization: "Bearer" + this.token,
  });

  header_put:HttpHeaders = new HttpHeaders({
    Authorization: "Bearer" + this.token,
  })
  // .set('Content-Type', 'application/x-www-form-urlencoded');

  constructor(
    public _http: HttpClient,
    public jwtHelper: JwtHelperService,
    private router: Router
  ) {
    this.url = global.url;
  }

  crear_historial(form): Observable<any> {
    let url = `${this.url}vacaciones/historial/crear`;
    return this._http.post(url, form, {
      headers: this.header,
    });
  }
  listar_historial(): Observable<any> {
    let url = `${this.url}vacaciones/historial/listar`;
    return this._http.get(url, {
      headers: this.header,
    });
  }

  listar_historial_por_vacacion_historial(vac_hist_id): Observable<any> {
    let url = `${this.url}vacaciones/historial/listar/${vac_hist_id}`;
    return this._http.get(url, {
      headers: this.header,
    });
  }
  crear_solicitud_vacaciones(form):Observable<any>{
    let url = `${this.url}vacaciones/solicitud/crear`;
    return this._http.post(url, form, {
      headers: this.header,
    });
  }
  editar_solicitud_vacaciones(form):Observable<any>{
    let url = `${this.url}vacaciones/solicitud/editar`;
    return this._http.put(url,form, {
      headers: this.header_put,
    });
  }
  listar_solicitud_vacaciones(id):Observable<any>{
    let url = `${this.url}vacaciones/solicitud/listar/${id}`;
    return this._http.get(url, {
      headers: this.header,
    });
  }
  detectar_incremento_mensaual_diasbasicos(id):Observable<any>{
    let url = `${this.url}vacaciones/solicitud/detectar_incremento_mensaual_diasbasicos/${id}`;
    return this._http.get(url, {
      headers: this.header,
    });
  }

  historial_dias_basicos_devengados(vac_historial_id):Observable<any>{
    let url = `${this.url}vacaciones/solicitud/historia-mensual-dias-basicos-devengados/${vac_historial_id}`;
    return this._http.get(url, {
      headers: this.header,
    });
  }
  dias_progresivos_devengados_disponibles(vac_historial_id):Observable<any>{
    let url = `${this.url}vacaciones/solicitud/dias_progresivos_devengados_disponibles/${vac_historial_id}`;
    return this._http.get(url, {
      headers: this.header,
    });
  }

  historial_dias_progresivos_devengados(vac_historial_id):Observable<any>{
    let url = `${this.url}vacaciones/solicitud/historial_dias_progresivos_devengados/${vac_historial_id}`;
    return this._http.get(url, {
      headers: this.header,
    });
  }
  lista_dias_progresivos(vac_historial_id):Observable<any>{
    let url = `${this.url}vacaciones/listado_dias_progresivos/${vac_historial_id}`;
    return this._http.get(url, {
      headers: this.header,
    });

  }

  crear_dia_progresivo(form):Observable<any>{
    let url = `${this.url}vacaciones/dias_progresivos/registrar`;
    return this._http.post(url, form, {
      headers: this.header,
    });
  }

  subir_comprobante(data):Observable<any>{
    let url = `${this.url}vacaciones/solicitud/subir_archivo`;
    return this._http.post(url, data, {
      headers: this.header,
    });
  }


}
