import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-asistencia-reunion',
  templateUrl: './asistencia-reunion.component.html',
  styleUrls: ['./asistencia-reunion.component.css']
})
export class AsistenciaReunionComponent implements OnInit {

  //variable para asociar al modal
  modalVariable;

  constructor(config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModal(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'xl'});
  }

  abrirConfirmacion(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'lg', centered: true});
  }

}
