import { Component, OnInit } from '@angular/core';
import { UsuarioService } from 'src/app/servicios/usuarios.service';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-navbar-socio',
  templateUrl: './navbar-socio.component.html',
  styleUrls: ['./navbar-socio.component.css']
})
export class NavbarSocioComponent implements OnInit {

  nombreSocio;
  rol;

  constructor(private _userService: UsuarioService, private _portalSociosService: PortalSociosService, private router: Router) { }

  ngOnInit() {
    //Guardar nombre socio
    this._portalSociosService.getSocioLogeado().subscribe((res) => {
      this.nombreSocio = res.nombres;
    });
    //obtener rol para habilitar boton cambio de portal
    this.rol = localStorage.getItem('rol');
  }

  logOut() {
    console.log('test');
    this._userService.logOut().subscribe(
      res => {
        localStorage.clear();
        this.router.navigate(['']);
      }
    );
    /*     localStorage.clear();
            this.router.navigate(['']);
        console.log('kkck'); */
  }

}
