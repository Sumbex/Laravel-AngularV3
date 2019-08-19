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
    console.log(this.datosPadresSuegros);
    this._portalSociosService.setDatosPadresSuegros(this.datosPadresSuegros).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        alert(response.mensaje);
      }
    },
    error => {
      console.log(error);
    });
  }

}
