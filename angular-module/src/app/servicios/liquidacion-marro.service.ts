import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, } from '@angular/common/http';
import { Observable } from 'rxjs';
import { global } from './global';

@Injectable({
  providedIn: 'root'
})
export class LiquidacionMarroService {

  public url: string;
    token = localStorage.getItem('token').replace(/['"]+/g, '');

    constructor(public _http: HttpClient) {
        this.url = global.url;
    }

    //PETICIONES PARA LA GENERACION DE LIQUIDACIONES
    getConfiguracionHaberesPorIdEmpleado(idEmpleado): Observable<any> {
        return this._http.get(this.url + "getConfiguracionHaberesPorIdEmpleado/" + idEmpleado, {
            headers: new HttpHeaders(
                {
                    'Authorization': 'Bearer' + this.token,
                    'Content-Type': 'applcation/json'
                }
            )
        });
    }

    getConfiguracionDescuentosPorIdEmpleado(idEmpleado): Observable<any> {
        return this._http.get(this.url + "getConfiguracionDescuentosPorIdEmpleado/" + idEmpleado, {
            headers: new HttpHeaders(
                {
                    'Authorization': 'Bearer' + this.token,
                    'Content-Type': 'applcation/json'
                }
            )
        });
    }

    //PETICIONES PARA EL HISTORIAL DE LIQUIDACIONES

    getLiquidacionesEmpleado(idEmpleado): Observable<any> {
      return this._http.get(this.url + "getLiquidacionesPorId/" + idEmpleado, {
          headers: new HttpHeaders(
              {
                  'Authorization': 'Bearer' + this.token,
                  'Content-Type': 'applcation/json'
              }
          )
      });
  }

  setDatosliquidacion(id, formulario): Observable<any>{
    let token = localStorage.getItem('token').replace(/['"]+/g, '');
    const body = new FormData();
    body.append('id', id);
    body.append('fecha', formulario.fecha);
    body.append('archivo', formulario.archivo);
    return this._http.post(this.url + "setDatosLiquidacion", body, {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + token
        }
    )});
}

actualizarLiquidacion(id, campo, input): Observable<any>{
    let token = localStorage.getItem('token').replace(/['"]+/g, '');
    const body = new FormData();
    body.append('id', id);
    body.append('campo', campo);
    body.append('input', input);
    return this._http.post(this.url + "actualizarLiquidacion", body, {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + token
        }
    )});
}

setHistorialLiquidacion(id, fecha, confHaberes, confDescuentos): Observable<any>{
    let token = localStorage.getItem('token').replace(/['"]+/g, '');
    return this._http.post(this.url + "setHistorialLiquidacion", {id,fecha,confHaberes,confDescuentos}, {headers: new HttpHeaders(
        {
            'Authorization': 'Bearer' + token
        }
    )});
}

// Variable para las liquidaciones generadas
getLiquidacionesGeneradasPorIdEmpleado(idEmpleado): Observable<any> {
    return this._http.get(this.url + "getLiquidacionesGeneradasPorIdEmpleado/" + idEmpleado, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )
    });
}

// Funcion para traer los detalles de las liquidaciones generadas
getHaberesPorIdLiquidacion(idEmpleado): Observable<any> {
    return this._http.get(this.url + "getHaberesPorIdLiquidacion/" + idEmpleado, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )
    });
}

getDescuentosPorIdLiquidacion(idEmpleado): Observable<any> {
    return this._http.get(this.url + "getDescuentosPorIdLiquidacion/" + idEmpleado, {
        headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )
    });
}

}
