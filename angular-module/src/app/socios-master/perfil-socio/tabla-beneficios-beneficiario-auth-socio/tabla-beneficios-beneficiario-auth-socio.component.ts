import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-tabla-beneficios-beneficiario-auth-socio',
  templateUrl: './tabla-beneficios-beneficiario-auth-socio.component.html',
  styleUrls: ['./tabla-beneficios-beneficiario-auth-socio.component.css']
})
export class TablaBeneficiosBeneficiarioAuthSocioComponent implements OnInit {

  //variable del modal
  abrirModalBeneficiosBeneficiario;

  //objeto con los datos del beneficiario
  datosBeneficiario;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  verTablaBeneficiario(tablaBeneficiario){
    this.abrirModalBeneficiosBeneficiario = this.modalService.open(tablaBeneficiario, { size: 'xl' });
    this.getDatosBeneficiario();
  }

  getDatosBeneficiario(){
    //llamar al servici para llenar la variable datosBeneficiario
    this._portalSociosService.getDatosBeneficiario().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosBeneficiario = response;
        console.log(response);
      }
    },
    error => {
      console.log(error);
    });
  }

}
