import { Component, OnInit } from '@angular/core';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-formulario-beneficios-beneficiario-auth-socio',
  templateUrl: './formulario-beneficios-beneficiario-auth-socio.component.html',
  styleUrls: ['./formulario-beneficios-beneficiario-auth-socio.component.css']
})
export class FormularioBeneficiosBeneficiarioAuthSocioComponent implements OnInit {

  //modal de formulario
  abrirFormularioBeneficiario

  datosBeneficiario = {
    relacionParentesco: '',
    rut: '',
    fechaNacimiento: '',
    nombres: '',
    apellidoPaterno: '',
    apellidoMaterno: '',
    direccion: '',
    celular: ''
  }

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModalBeneficiario(formularioBeneficiario){
    this.abrirFormularioBeneficiario = this.modalService.open(formularioBeneficiario, { size: 'xl' });
  }

  setDatosBeneficiario(){
    //Llamar al servicio para ingresar datos del beneficiario
    console.log(this.datosBeneficiario);
  }

}
