import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable()
export class SindicalService{
    public url: string;
    token = localStorage.getItem('token').replace(/['"]+/g, '');

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    ingresarValor(form): Observable<any>{
    
        const body = new FormData();
        body.append('fecha', form.fecha);
        body.append('n_documento', form.nDocumento);
        body.append('archivo', form.archivoDocumento);
        body.append('tipo_cuenta_sindicato', form.tipoCuentaSindicato);
        body.append('descripcion', form.descripcion);
        body.append('definicion', form.definicion);
        body.append('monto', form.monto);

        return this._http.post(this.url + "guardar_item_c_s", body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token
            }
        )});
    }

    getTablaSindical(anio: string, mes: string): Observable<any>{
        return this._http.get(this.url + "listar_c_s/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    
    getTablaSindicalMontoInicial(anio: string, mes: string): Observable<any>{
        return this._http.get(this.url + "traer_monto_inicial_cs/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    getTablaSindicalActualizar(id,campo,input): Observable<any>{

        const body = new FormData();
        body.append('id', id);
        body.append('campo', campo);
        body.append('input', input);

        return this._http.post(this.url + "actualizar_dato_cs",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                //'Content-Type': 'applcation/json'
            }
        )});
    }

    getCalcularCajaChica(anio: string, mes: string): Observable<any>{
        return this._http.get(this.url + "calcular_caja_chica_anterior_cs/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    getCalcularCajaChicaActualizar(anio: string, mes: string): Observable<any>{
        return this._http.get(this.url + "calcular_caja_chica_anterior_cs2/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    getTipoPrestamo(){
        return this._http.get<any>(this.url + "traer_tipo_prestamo", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'application/json'
            }
        )});
    }

    setPrestamo(form): Observable<any>{
        const body = new FormData();
        body.append('fecha', form.fecha);
        body.append('select_id', form.selectId);
        body.append('socio_id', form.socioId);
        body.append('numero_documento', form.numeroDocumento);
        body.append('archivo_documento', form.archivoDocumento);
        body.append('monto_total', form.montoPrestamo);
        body.append('checkAbono', form.checkAbono);
        body.append('cuotas', form.cuotas);
        body.append('monto_dia', form.montoDia);
        body.append('monto_tri', form.montoTri);
        body.append('monto_con', form.montoCon);
        body.append('checkdia', form.checkDia);
        body.append('checktri', form.checkTri);
        body.append('checkcon', form.checkCon);

        return this._http.post(this.url + "guardar_prestamo",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token
            }
        )});
    }

    getPrestramosSocios(anio: string, mes: string): Observable<any>{
        return this._http.get(this.url + "traer_prestamos/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    getPrestamosHistoricos(anio: string, mes: string): Observable<any>{
        return this._http.get(this.url + "listar_p_salud_retornable/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    pagarPrestamo(fecha, id, monto): Observable<any>{
        const body = new FormData();
        body.append('detalle_prestamo_id', id);
        body.append('fecha', fecha);
        body.append('monto', monto);

        return this._http.post(this.url + "ingresar_pago_prestamo",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token
            }
        )});
    }

    pagarPrestamoSalud(fecha, id, monto): Observable<any>{
        const body = new FormData();
        body.append('p_salud_retornable_id', id);
        body.append('fecha', fecha);
        body.append('monto', monto);

        return this._http.post(this.url + "pagar_p_salud_retornable",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token
            }
        )});
    }
    pagarPrestamoApuro(fecha, id, monto): Observable<any>{
        const body = new FormData();
        body.append('p_apuro_id', id);
        body.append('fecha', fecha);
        body.append('monto', monto);

        return this._http.post(this.url + "pagar_p_apuro_economico_retornable",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token
            }
        )});
    }

    pagarAbono(id, definicionSelectAbono, fecha, monto): Observable<any>{
        const body = new FormData();
        body.append('p_salud_retornable_id', id);
        body.append('fecha', fecha);
        body.append('abono', definicionSelectAbono);
        body.append('monto', monto);

        return this._http.post(this.url + "pago_abono",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token
            }
        )});
    }
    listar_abonos_por_prestamo(id, tipo): Observable<any>{

        return this._http.get(this.url + "listar_abonos_por_prestamo/" + id + "/" + tipo, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
    
    lista_ultima_prestamos(anio, mes): Observable<any>{

        return this._http.get(this.url + "tabla_final_prestamo/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
    listar_pni(): Observable<any>{

        return this._http.get(this.url + "listar_prestamos_vigentes", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
     listar_pc(): Observable<any>{

        return this._http.get(this.url + "listar_prestamos_finalizados", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                'Content-Type': 'applcation/json'
            }
        )});
    }


    
}