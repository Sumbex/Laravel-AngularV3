import { Injectable } from '@angular/core';
import { Router, CanActivate } from '@angular/router';
import { UsuarioService } from '../servicios/usuarios.service';
import { PortalSociosService } from '../servicios/portal-socios.service';
import { stringify } from 'querystring';

@Injectable()
export class AuthGuardSocioService implements CanActivate {

    rol = localStorage.getItem('rol');

    constructor(public auth: UsuarioService, public _portalSociosService: PortalSociosService, public router: Router) {
    }

    canActivate(): boolean {
        if (this.rol != '10' || !this.auth.isAuthenticated()) {
            this.router.navigate(['']);
            return false;
        }
        return true;
    }
}