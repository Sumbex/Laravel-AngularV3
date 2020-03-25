import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class LiquidacionEmpanadaService {
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) {
    this.url = global.url;
  }

  crearEmpleado(empleadoForm): Observable<any> {

    const form = new FormData();
    form.append('rut_trabajador', empleadoForm.rut_trabajador)
    form.append('nombre_trabajador', empleadoForm.nombre_trabajador)
    form.append('cargo', empleadoForm.cargo)
    form.append('nombre_afp', empleadoForm.nombre_afp)
    form.append('nombre_isapre', empleadoForm.nombre_isapre)
    form.append('rut_empresa', empleadoForm.rut_empresa)
    form.append('nombre_empresa', empleadoForm.nombre_empresa)
    form.append('direccion_empresa', empleadoForm.direccion_empresa)

    return this._http.post(this.url + "crear_empleado", form, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
        }
      )
    });
  }

  actualizarEmpleado(idEmpleado,actualizarEmpleado): Observable<any> {

    const form = new FormData();
    form.append('id', idEmpleado)
    form.append('rut_trabajador', actualizarEmpleado.rut_trabajador)
    form.append('nombre_trabajador', actualizarEmpleado.nombre_trabajador)
    form.append('cargo', actualizarEmpleado.cargo)
    form.append('nombre_afp', actualizarEmpleado.nombre_afp)
    form.append('nombre_isapre', actualizarEmpleado.nombre_isapre)
    form.append('rut_empresa', actualizarEmpleado.rut_empresa)
    form.append('nombre_empresa', actualizarEmpleado.nombre_empresa)
    form.append('direccion_empresa', actualizarEmpleado.direccion_empresa)

    return this._http.post(this.url + "actualizar_empleado", actualizarEmpleado, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
        }
      )
    });
  }

  traerEmpleados(): Observable<any>{
    let token = localStorage.getItem('token').replace(/['"]+/g, '');
    return this._http.get(this.url + "traer_empleados", {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + token,
            'Content-Type': 'applcation/json'
        }
    )});
  }

}
