import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

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

  constructor(config: NgbModalConfig, private modalService: NgbModal) {
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
    console.log(this.datosPadresSuegros);
  }

}
