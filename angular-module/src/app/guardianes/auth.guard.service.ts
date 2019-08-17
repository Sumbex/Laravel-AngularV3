import { Injectable } from '@angular/core';
import { Router, CanActivate } from '@angular/router';
import { UsuarioService } from '../servicios/usuarios.service';
import { stringify } from 'querystring';

@Injectable()
export class AuthGuardService implements CanActivate {

    rol = localStorage.getItem('rol');

    constructor(public auth: UsuarioService, public router: Router) {}

    canActivate(): boolean {
        if ((this.rol != '1' && this.rol != '5') || !this.auth.isAuthenticated()) {
            this.router.navigate(['']);
            return false;
        }
        return true;
    }
}