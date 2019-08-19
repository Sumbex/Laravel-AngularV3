import { Component, OnInit } from '@angular/core';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-beneficios-padres-suegros-auth-socio',
  templateUrl: './tabla-beneficios-padres-suegros-auth-socio.component.html',
  styleUrls: ['./tabla-beneficios-padres-suegros-auth-socio.component.css']
})
export class TablaBeneficiosPadresSuegrosAuthSocioComponent implements OnInit {

  //Variable del modal
  abrirModalPadresSuegros;

  //Objetoq ue almacena los datos a ingresar
  datosPadresSuegros;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
  }

  openModalPadresSuegros(modalPadresSuegros){
    this.abrirModalPadresSuegros = this.modalService.open(modalPadresSuegros, {size: 'xl'});
    this.getDatosPadresSuegros();
  }

  getDatosPadresSuegros(){
    //Llamar al servicio para almacenar los datos
    this._portalSociosService.getDatosPadresSuegros().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosPadresSuegros = response.padres_suegros;
        console.log(this.datosPadresSuegros);
      }
    },
    error => {
      console.log(error);
    });
  }

}
