import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-formulario-beneficios-padres-suegros-auth-socio',
  templateUrl: './formulario-beneficios-padres-suegros-auth-socio.component.html',
  styleUrls: ['./formulario-beneficios-padres-suegros-auth-socio.component.css']
})
export class FormularioBeneficiosPadresSuegrosAuthSocioComponent implements OnInit {

  //Variable para el modal
  abrirModalFormularioPadresSuegros;

  //Objeto que almacena los datos
  datosPadresSuegros={
    relacionSocioId:'',
    rut:'',
    fechaNacimiento:'',
    nombres:'',
    apellidoPaterno:'',
    apellidoMaterno:'',
    direccion:'',
    celular:''
  }

  //variable para bloquear el doble ingreso
  blockIngreso = false;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModalPadresSuegros(modalPadresSuegros){
    this.abrirModalFormularioPadresSuegros = this.modalService.open(modalPadresSuegros, {size: 'xl'});
  }

  setDatosPadresSuegros(){
    //Aquí se dede de llamar al servicio para ingresar los datos del objeto datosPadresSuegros
    this.blockIngreso = true;
    this._portalSociosService.setDatosPadresSuegros(this.datosPadresSuegros).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert('Revise el formulario, que no falte un campo o que exista un dato invalido');
        this.blockIngreso = false;
      }else{
        alert(response.mensaje);
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
    this.datosPadresSuegros.relacionSocioId='';
    this.datosPadresSuegros.rut='';
    this.datosPadresSuegros.fechaNacimiento='';
    this.datosPadresSuegros.nombres='';
    this.datosPadresSuegros.apellidoPaterno='';
    this.datosPadresSuegros.apellidoMaterno='';
    this.datosPadresSuegros.direccion='';
    this.datosPadresSuegros.celular='';
  }

}
