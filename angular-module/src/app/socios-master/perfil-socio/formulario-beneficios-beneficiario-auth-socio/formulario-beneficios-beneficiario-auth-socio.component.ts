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

  //variable para bloquear el doble ingreso
  blockIngreso = false;

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
    this.blockIngreso = true;
    //Llamar al servicio para ingresar datos del beneficiario
    console.log(this.datosBeneficiario);
    this._portalSociosService.setDatosBeneficiarios(this.datosBeneficiario).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert('Revise el formulario, que no falte un campo o que exista un dato invalido');
        this.blockIngreso = false;
      }else{
        alert('Ingreso del Beneficiario correcto');
        this.limpiarDatos();
        this.blockIngreso = false;
      }
    },
    error => {
      console.log(error);
      this.blockIngreso = false;
    });
  }

  limpiarDatos(){
    this.datosBeneficiario.relacionParentesco= '';
    this.datosBeneficiario.rut= '';
    this.datosBeneficiario.fechaNacimiento= '';
    this.datosBeneficiario.nombres= '';
    this.datosBeneficiario.apellidoPaterno= '';
    this.datosBeneficiario.apellidoMaterno= '';
    this.datosBeneficiario.direccion= '';
    this.datosBeneficiario.celular= '';
  }

}
