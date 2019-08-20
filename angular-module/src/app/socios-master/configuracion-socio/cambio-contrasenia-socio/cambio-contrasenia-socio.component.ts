import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-cambio-contrasenia-socio',
  templateUrl: './cambio-contrasenia-socio.component.html',
  styleUrls: ['./cambio-contrasenia-socio.component.css']
})
export class CambioContraseniaSocioComponent implements OnInit {

  //Variable para asociar el modal
  modalCambioPass;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSocioService: PortalSociosService, private _router: Router) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  openModalPassCambio(cambioPass){
    this.modalCambioPass = this.modalService.open(cambioPass, {size: 'lg'});
  }

  cambiarPass(passActual, passNueva, passNuevaValida){
    //Aquí llamar al servicio para cambiar pass
    this._portalSocioService.cambiarPassSocio(passActual.value, passNueva.value, passNuevaValida.value).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(JSON.stringify(response.mensaje));
      }else{
        alert(response.mensaje);
        this.modalCambioPass.close();
        this._router.navigate(['/']);
      }
    },
    error => {
      console.log(error);
    });
  }

}
