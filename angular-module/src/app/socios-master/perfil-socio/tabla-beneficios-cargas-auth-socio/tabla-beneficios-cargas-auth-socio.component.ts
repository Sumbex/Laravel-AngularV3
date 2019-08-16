import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-beneficios-cargas-auth-socio',
  templateUrl: './tabla-beneficios-cargas-auth-socio.component.html',
  styleUrls: ['./tabla-beneficios-cargas-auth-socio.component.css']
})
export class TablaBeneficiosCargasAuthSocioComponent implements OnInit {

  //variable para el modal cargas
  abrirModalTablaCargas;

  //objeto que guardara los datos obtenidos de las cargas
  datosCargas;

  constructor(config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModalCargas(modalCargas){
    this.abrirModalTablaCargas = this.modalService.open(modalCargas, {size: 'xl'});
    this.getDatosCargas();
  }

  getDatosCargas(){
    //Aquí se llamá al servicio para obtener los datos de las cargas
  }

}
