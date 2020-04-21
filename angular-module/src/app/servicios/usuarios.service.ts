import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, } from '@angular/common/http';
import { Observable } from 'rxjs';
import { global } from './global';
import { JwtHelperService } from '@auth0/angular-jwt';
import { Router } from '@angular/router';
import { map } from 'rxjs/operators';

@Injectable()
export class UsuarioService {
    public url: string;
    token = '';
    rol = '';
    public usuario: string;

    constructor(public _http: HttpClient, public jwtHelper: JwtHelperService, private router: Router) {
        this.url = global.url;
    }

    register(user): Observable<any> {
        let json = JSON.stringify(user);
        let params = 'json=' + json;

        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

        return this._http.post(this.url + 'register', params, { headers: headers });
    }

    login(user, getToken = null): Observable<any> {
        if (getToken != null) {
            user.getToken = 'true';
        }

        let json = JSON.stringify(user);
        //let params = 'json='+json;

        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

        return this._http.post(this.url + 'login_rut?', "email=" + user.email + "&" + "password=" + user.password, { headers: headers })
    }

    getToken() {
        let token = localStorage.getItem('token');
        if (token && token != "undefinided") {
            this.token = token;
        } else {
            this.token = null;
        }

        return this.token;
    }

    getUsuario() {
        let user = localStorage.getItem('usuario').replace(/['"]+/g, '');;
        if (user && user != "undefinided") {
            this.usuario = user;
        } else {
            this.usuario = null;
        }

        return this.usuario;
    }

    isAuthenticated(): boolean {
        if (localStorage.getItem('token') === null) {
            return false;
        } else {
            const token = localStorage.getItem('token').replace(/['"]+/g, '');    // Check whether the token is expired and return
            // true or false
            return !this.jwtHelper.isTokenExpired(token);
        }
    }

    logOutSocio(): Observable<any> {
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "auth/logout_socio", {
            headers: new HttpHeaders(
                { 'Authorization': 'Bearer' + token }
            )
        });
    }

    logOut(): Observable<any> {
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "auth/logout", {
            headers: new HttpHeaders(
                { 'Authorization': 'Bearer' + token }
            )
        });
    }

    validarUsuario(user, password, estado): Observable<any> {
        let token = localStorage.getItem('token').replace(/['"]+/g, '');

        const formData = new FormData();
        formData.append('rut', user);
        formData.append('password', password);
        formData.append('estado', estado);

        return this._http.post(this.url + "confirmar_usuario", formData, {
            headers: new HttpHeaders(
                {
                    'Authorization': 'Bearer' + token
                }
            )
        });
    }

    getUsuarioLogeado(): Observable<any> {
        let token = localStorage.getItem('token').replace(/['"]+/g, '');
        return this._http.get(this.url + "usuario_logeado", {
            headers: new HttpHeaders(
                { 'Authorization': 'Bearer' + token })
        }
        );
    }

}