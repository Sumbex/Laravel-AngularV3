import { Component, OnInit } from '@angular/core';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-formulario-beneficios-conyuge-auth-socio',
  templateUrl: './formulario-beneficios-conyuge-auth-socio.component.html',
  styleUrls: ['./formulario-beneficios-conyuge-auth-socio.component.css']
})
export class FormularioBeneficiosConyugeAuthSocioComponent implements OnInit {

  //Modal
  abrirFormularioBeneficiosConyuge;

  //Objeto con los datos del conyuge
  datosConyuge = {
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

  verFormularioConyuge(formularioBeneficiosConyuge){
    this.abrirFormularioBeneficiosConyuge = this.modalService.open(formularioBeneficiosConyuge, { size: 'xl' });
  }

  setDatosConyuge(){
    //Llamar al servicio para ingresar los valores del objeto
    console.log(this.datosConyuge);
  }

}
