import { Injectable } from '@angular/core';
import { Router, CanActivate } from '@angular/router';
import { UsuarioService } from '../servicios/usuarios.service';

@Injectable()
export class AuthGuardSocioService implements CanActivate {

    usuario = 'admin';

    constructor(public auth: UsuarioService, public router: Router) { } 

    canActivate(): boolean {
        if(this.usuario != 'socio' && !this.auth.isAuthenticated()){
            this.router.navigate(['']);
            return false;
        }
        return true;
    }
}