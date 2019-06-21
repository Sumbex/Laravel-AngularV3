import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Usuario } from '../modelos/usuarios.model';
import { global } from './global';

@Injectable()
export class UsuarioService{
    public url: string;
    public token: string;
    public usuario: string;

    constructor(public _http: HttpClient){
        this.url = global.url;
    }

    register(user): Observable<any>{
        let json = JSON.stringify(user);
        let params = 'json='+json;

        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')

        return this._http.post(this.url+'register', params, {headers: headers});
    }

    login(user, getToken = null): Observable<any>{
        if(getToken != null){
            user.getToken = 'true';
        }

        let json = JSON.stringify(user);
        //let params = 'json='+json;

        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')

        return this._http.post(this.url+'login?', "email=" + user.email + "&" + "password=" + user.password, {headers: headers});
    }

    getToken(){
        let token = localStorage.getItem('token');
        if(token && token != "undefinided"){
            this.token = token;
        }else{
            this.token = null;
        }

        return this.token;
    }

    getUsuario(){
        let user = localStorage.getItem('usuario');
        if(user && user != "undefinided"){
            this.usuario = user;
        }else{
            this.usuario = null;
        }

        return this.usuario;
    }

}