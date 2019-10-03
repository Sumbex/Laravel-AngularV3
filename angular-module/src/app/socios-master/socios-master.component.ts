import { Component, OnInit } from '@angular/core';
import { UsuarioService } from '../servicios/usuarios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from '../servicios/portal-socios.service';
import { map } from 'rxjs/operators';
import { Router } from '@angular/router';

@Component({
  selector: 'app-socios-master',
  templateUrl: './socios-master.component.html',
  styleUrls: ['./socios-master.component.css']
})
export class SociosMasterComponent implements OnInit {

  tiempoEsperaToken: number = 5;

  constructor(private _usuariosService: UsuarioService, public _portalSociosService: PortalSociosService, private _getAnios: PortalSociosService, private router: Router) {
  }

  ngOnInit() {
    //Guardar Anios
    this._getAnios.getAnios().subscribe((res) => {
      let filtroAnios = res.map(({id,descripcion}) => ({id,descripcion}));
      localStorage.setItem('anios', JSON.stringify(filtroAnios));
    });
    //Guardar Meses
    this._getAnios.getMeses().subscribe((res) => {
      let filtroMeses = res.map(({id,descripcion}) => ({id,descripcion}));
      localStorage.setItem('meses', JSON.stringify(filtroMeses));
    });
    this.startTimerToken();
  }

  startTimerToken() {
    setInterval(() => {
      if (this.tiempoEsperaToken > 0) {
        this.tiempoEsperaToken--;
      } else {
        let estadoToken = this._usuariosService.isAuthenticated();
        console.log(estadoToken);
        if (estadoToken == false) {
          this.router.navigate(['']);
        } else {
          this.tiempoEsperaToken = 5;
        }
      }
    }, 1000)
  }

}
