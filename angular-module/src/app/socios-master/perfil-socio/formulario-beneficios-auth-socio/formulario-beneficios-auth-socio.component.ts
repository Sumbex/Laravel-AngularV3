import { Component, OnInit } from '@angular/core';
import { NgbModal, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-formulario-beneficios-auth-socio',
  templateUrl: './formulario-beneficios-auth-socio.component.html',
  styleUrls: ['./formulario-beneficios-auth-socio.component.css']
})
export class FormularioBeneficiosAuthSocioComponent implements OnInit {

  //Instancia del modal
  abrirFormularioBeneficiosSocios;

  //Objeto con los datos del socio
  datosSocio = {
    numeroCuenta: '',
    banco: '',
    tipoCuenta: '0',
    isaFona: '',
    grupoSangre: '',
    direccion: '',
    region: '',
    provincia: '',
    comuna: '',
    telefono: '',
    celular: '',
    anexo: '',
    emailPersonal: '',
    emailCorporativo: '',
    cargoPlanta: '',
    cargoComisionSindicato: '0',
    casaPropia: '0',
    rolTurno: '0',
    estadoCivil: '0',
    conyugePareja: '0'
  }

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
  }

  verFormularioBeneficios(FormularioBeneficios) {
    this.abrirFormularioBeneficiosSocios = this.modalService.open(FormularioBeneficios, { size: 'xl' });
  }

  ingresarDatosSocio(){
    console.log(this.datosSocio);
  }

}
