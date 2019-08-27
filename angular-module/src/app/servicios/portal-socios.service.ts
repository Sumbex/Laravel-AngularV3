import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from './global';
import { Observable } from 'rxjs';

@Injectable()
export class PortalSociosService{
    public url: string;
    rol = '';
    //token = localStorage.getItem('token').replace(/['"]+/g, '');

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    loginSocios(form) : Observable<any>{
        const body = new FormData();
        body.append('rut',form.email);
        body.append('password', form.password);

        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

        return this._http.post(this.url + "login_socios?", "rut=" + form.email + "&" + "password=" + form.password, {headers: headers});

    }

    getSocioLogeado() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "socio_logeado",{headers: new HttpHeaders(
            {'Authorization': 'Bearer' + token})}
    );
    }

    getDatosSocios() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_datos_basicos_socio", {headers: new HttpHeaders(
            {'Authorization': 'Bearer' + token}
        )});
    }

    setDatosBasicosSocios(form): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('direccion', form.direccion);
        body.append('telefono', form.telefono);
        body.append('celular', form.celular);
        body.append('anexo', form.anexo);
        body.append('email_1', form.email_1);
        body.append('email_2', form.email_2);
        body.append('cargo_planta', form.cargo_planta);
        body.append('cargo_comision_sindicato', form.cargo_comision_sindicato);
        body.append('casa_propia', form.casa_propia);
        body.append('rol_turno', form.rol_turno);
        body.append('estado_civil_id', form.estado_civil_id);
        body.append('conyuge', form.conyuge);
        body.append('numero_cuenta', form.numero_cuenta);
        body.append('tipo_cuenta_banco_id', form.tipo_cuenta_banco_id);
        body.append('banco', form.banco);
        body.append('isapre_fonasa', form.isapre_fonasa);
        body.append('grupo_sangre', form.grupo_sangre);

        console.log(form);

        return this._http.post(this.url + "ingresar_datos_basicos_socio",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    setDatosConyugeSocio(form): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('rut', form.rut);
        body.append('fecha_nacimiento', form.fechaNacimiento);
        body.append('nombres', form.nombres);
        body.append('apellido_paterno', form.apellidoPaterno);
        body.append('apellido_materno', form.apellidoMaterno);
        body.append('direccion', form.direccion);
        body.append('celular', form.celular);
        body.append('archivo', form.archivoDocumento);

        return this._http.post(this.url + "ingresar_datos_conyuge_socio",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    getDatosConyuge() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_datos_conyuge_socio", {headers: new HttpHeaders(
            {'Authorization': 'Bearer' + token}
        )});
    }

    setDatosBeneficiarios(form): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('relacion', form.relacionParentesco);
        body.append('rut', form.rut);
        body.append('fecha_nacimiento', form.fechaNacimiento);
        body.append('nombres', form.nombres);
        body.append('apellido_paterno', form.apellidoPaterno);
        body.append('apellido_materno', form.apellidoMaterno);
        body.append('direccion', form.direccion);
        body.append('celular', form.celular);

        return this._http.post(this.url + "ingresar_datos_beneficiarios_socio",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    getDatosBeneficiario() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_datos_beneficiarios_socio", {headers: new HttpHeaders(
            {'Authorization': 'Bearer' + token}
        )});
    }

    setDatosCargas(form): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('tipo_carga_id', form.tipoCargaId);
        body.append('rut', form.rut);
        body.append('fecha_nacimiento', form.fechaNacimiento);
        body.append('nombres', form.nombres);
        body.append('apellido_paterno', form.apellidoPaterno);
        body.append('apellido_materno', form.apellidoMaterno);
        body.append('direccion', form.direccion);
        body.append('celular', form.celular);
        body.append('establecimiento', form.establecimiento);
        body.append('archivo', form.archivoDocumento);

        return this._http.post(this.url + "ingresar_datos_cargas_socio",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    getDatosCargas() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_datos_cargas_socio", {headers: new HttpHeaders(
            {'Authorization': 'Bearer' + token}
        )});
    }

    setDatosPadresSuegros(form): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('relacion_socio_id', form.relacionSocioId);
        body.append('rut', form.rut);
        body.append('fecha_nacimiento', form.fechaNacimiento);
        body.append('nombres', form.nombres);
        body.append('apellido_paterno', form.apellidoPaterno);
        body.append('apellido_materno', form.apellidoMaterno);
        body.append('direccion', form.direccion);
        body.append('celular', form.celular);

        return this._http.post(this.url + "ingresar_datos_padres_suegros_socio",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    getDatosPadresSuegros() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_datos_padres_suegros_socio", {headers: new HttpHeaders(
            {'Authorization': 'Bearer' + token}
        )});
    }

    cambiarPassSocio(passAct, passNue, passCon) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('password', passAct);
        body.append('new_password', passNue);
        body.append('conf_new_password', passCon);

        return this._http.post(this.url + "cambiar_contrasena_socio",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    updateInfoSocio(valor, campo) : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        const body = new FormData();
        body.append('input', valor);
        body.append('nombre_campo', campo);

        return this._http.post(this.url + "modificar_datos_socio",body, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token
            }
        )});
    }

    getResumenSocio(): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_archivo_resumen_socio", {headers: new HttpHeaders(
            {'Authorization': 'Bearer' + token}
        )});
    }

    //OBTENER FECHAS PARA EL PORTAL DE SOCIOS

    getAnios() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get<any>(this.url + "traer_anios", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }

    getMeses() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get<any>(this.url + "traer_meses", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }

    getAnioActual() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_anio_actual", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }
    
    getMesActual() : Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_mes_actual", {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'application/json'
            }
        )});
    }

    //Servicio para llamar a la cuenta sindical
    getTablaSindical(anio: string, mes: string): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_cuenta_sindical_socio/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    //Servicio para llamar a la caja chica
    getCajaChica(anio: string, mes: string): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_caja_chica_socio/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }

    //Servicio para llamar al detalle Camping
    getCamping(anio: string, mes: string): Observable<any>{
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "traer_camping_socio/" + anio + "/" + mes, {headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + token,
                'Content-Type': 'applcation/json'
            }
        )});
    }
}