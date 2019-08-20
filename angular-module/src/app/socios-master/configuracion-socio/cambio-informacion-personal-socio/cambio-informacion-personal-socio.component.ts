import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-cambio-informacion-personal-socio',
  templateUrl: './cambio-informacion-personal-socio.component.html',
  styleUrls: ['./cambio-informacion-personal-socio.component.css']
})
export class CambioInformacionPersonalSocioComponent implements OnInit {

  //Variable para definir el modal
  abrirModalInfoPersonal;

  //Objeto que traera los datos iniciales
  infoPersonal;

  //variable para mostrar el loading
  cargandoTabla;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  vertTablaInfoPersonal(infoPersonal){
    this.abrirModalInfoPersonal = this.modalService.open(infoPersonal, {size: 'xl'});
    this.getInfoPersonal();
  }

  getInfoPersonal(){
    //Aquí se llama al servicio para obtener los datos personales
  }

  setNuevosDatos(){
    //Aquí se llama al servicio para ingresar los nuevos datos
  }
}
