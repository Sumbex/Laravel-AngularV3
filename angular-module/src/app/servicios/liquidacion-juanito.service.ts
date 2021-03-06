import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, } from '@angular/common/http';
import { Observable } from 'rxjs';
import { global } from './global';

@Injectable({
  providedIn: 'root'
})
export class LiquidacionJuanitoService {

    public url: string;
    token = localStorage.getItem('token').replace(/['"]+/g, '');

    constructor(public _http: HttpClient) {

      this.url = global.url;
    }

    lista_haberes(): Observable<any> {

      return this._http.get(this.url + "lista_haberes", {
          headers: new HttpHeaders(
            { 'Authorization': 'Bearer' + this.token })
        }
        )

    }

  
    save_haber(objeto): Observable<any> {

      return this._http.post(this.url + "guardar_config_haber", objeto, {
        headers: new HttpHeaders(
          { 'Authorization': 'Bearer' + this.token })
        }
      )

    }
  
  traer_empleados(): Observable<any> {

    return this._http.get(this.url + "traer_empleados", {
        headers: new HttpHeaders(
          { 'Authorization': 'Bearer' + this.token })
      }
      )

    }

    listar_haber(empleado): Observable<any> {

        return this._http.get(this.url + "lista_conf_haberes/"+empleado, {
          headers: new HttpHeaders(
            { 'Authorization': 'Bearer' + this.token })
        }
      )

    }

    eliminar_conf_haber(conf_hab_id): Observable<any> {

        return this._http.get(this.url + "eliminar_item_conf_hab/" + conf_hab_id, {
        headers: new HttpHeaders(
          { 'Authorization': 'Bearer' + this.token })
        }
      )

    }

  
  actualizar_conf_haber(form): Observable<any> {

    return this._http.post(this.url + "actualizar_conf_haber", form, {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    )

  }


  //descuentos
  // -----------------------

  lista_descuentos(): Observable<any> {

    return this._http.get(this.url + "lista_descuentos", {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    )

  }


  save_descuentos(objeto): Observable<any> {

    return this._http.post(this.url + "guardar_config_descuento", objeto, {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    )

  }

  traer_descuento(): Observable<any> {

    return this._http.get(this.url + "traer_empleados", {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    )

  }

  listar_descuentos(empleado): Observable<any> {

    return this._http.get(this.url + "lista_conf_descuentos/" + empleado, {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    )

  }

  eliminar_conf_descuento(conf_desc_id): Observable<any> {

    return this._http.get(this.url + "eliminar_item_conf_desc/" + conf_desc_id, {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    )

  }


  actualizar_conf_descuento(form): Observable<any> {

    return this._http.post(this.url + "actualizar_conf_descuento", form, {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    )

  }

  traer_total_h_i(empelado): Observable<any> {

    return this._http.get(this.url + "traer_total_h_i/" + empelado, {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    )

  }

  
  formula_desc(id_desc): Observable<any> {

    return this._http.get(this.url + "formula_desc/" + id_desc, {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    )

  }

  
}
