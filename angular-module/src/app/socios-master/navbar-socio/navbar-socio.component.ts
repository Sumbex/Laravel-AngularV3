import { Component, OnInit } from '@angular/core';
import { UsuarioService } from 'src/app/servicios/usuarios.service';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-navbar-socio',
  templateUrl: './navbar-socio.component.html',
  styleUrls: ['./navbar-socio.component.css']
})
export class NavbarSocioComponent implements OnInit {

  nombreSocio;

  constructor(private _userService: UsuarioService, private _portalSociosService: PortalSociosService) { }

  ngOnInit() {
    //Guardar nombre socio
    this._portalSociosService.getSocioLogeado().subscribe((res) => {
      this.nombreSocio = res.nombres;
    });
  }

  logOut(){
    this._userService.logOut();
  }

}
