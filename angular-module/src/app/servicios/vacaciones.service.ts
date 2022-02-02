import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Router } from "@angular/router";
import { JwtHelperService } from "@auth0/angular-jwt";
import { Observable } from "rxjs";
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
  listar_solicitud_vacaciones(id):Observable<any>{
    let url = `${this.url}vacaciones/solicitud/listar/${id}`;
    return this._http.get(url, {
      headers: this.header,
    });
  }
}
