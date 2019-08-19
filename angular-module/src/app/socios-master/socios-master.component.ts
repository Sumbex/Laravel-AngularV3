import { Component, OnInit } from '@angular/core';
import { UsuarioService } from '../servicios/usuarios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from '../servicios/portal-socios.service';

@Component({
  selector: 'app-socios-master',
  templateUrl: './socios-master.component.html',
  styleUrls: ['./socios-master.component.css']
})
export class SociosMasterComponent implements OnInit {

  tiempoEsperaToken: number = 1;

  constructor(private _usuariosService: UsuarioService, public _portalSociosService: PortalSociosService) {
  }

  ngOnInit() {
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
