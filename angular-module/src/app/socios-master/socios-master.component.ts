import { Component, OnInit } from '@angular/core';
import { UsuarioService } from '../servicios/usuarios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from '../servicios/portal-socios.service';
import { map } from 'rxjs/operators';
import { AniosService } from '../servicios/anios.service';

@Component({
  selector: 'app-socios-master',
  templateUrl: './socios-master.component.html',
  styleUrls: ['./socios-master.component.css']
})
export class SociosMasterComponent implements OnInit {

  tiempoEsperaToken: number = 1;

  constructor(private _usuariosService: UsuarioService, public _portalSociosService: PortalSociosService, private _getAnios: AniosService) {
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
        if (estadoToken == false) {
          window.location.reload();
        } else {
          this.tiempoEsperaToken = 1;
        }
      }
    }, 1000)
  }

}
