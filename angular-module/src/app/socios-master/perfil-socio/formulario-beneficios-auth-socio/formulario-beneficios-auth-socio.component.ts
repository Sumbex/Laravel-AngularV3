import { Component, OnInit } from '@angular/core';
import { NgbModal, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';

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
    tipoCuenta: '',
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
    cargoComisionSindicato: '',
    casaPropia: '',
    rolTurno: '',
    estadoCivil: '',
    conyugePareja: ''
  }

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
  }

  verFormularioBeneficios(FormularioBeneficios) {
    this.abrirFormularioBeneficiosSocios = this.modalService.open(FormularioBeneficios, { size: 'xl' });
  }

  test(){
    console.log(this.datosSocio);
  }

}
