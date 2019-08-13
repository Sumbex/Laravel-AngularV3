import { Injectable } from '@angular/core';
import { Router, CanActivate } from '@angular/router';
import { UsuarioService } from '../servicios/usuarios.service';
import { PortalSociosService } from '../servicios/portal-socios.service';

@Injectable()
export class AuthGuardSocioService implements CanActivate {

    usuario = 'admin';
    rol = '';

    constructor(public auth: UsuarioService, public _portalSociosService: PortalSociosService, public router: Router) {
        this._portalSociosService.getSocioLogeado().subscribe(
            response => {
                console.log(response);
                this.rol = response.rol;
                console.log(this.rol);
            },
            error => {
                console.log(error);
            }
        )
    } 

    canActivate(): boolean {
        if(this.usuario != 'socio' && !this.auth.isAuthenticated()){
            this.router.navigate(['']);
            return false;
        }
        return true;
    }
}