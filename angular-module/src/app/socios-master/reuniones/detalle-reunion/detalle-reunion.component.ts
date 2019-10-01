import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-detalle-reunion',
  templateUrl: './detalle-reunion.component.html',
  styleUrls: ['./detalle-reunion.component.css']
})
export class DetalleReunionComponent implements OnInit {

  //variable para alojar los datos del mensaje
  datosMensaje;

  //variable para asociar al modal
  modalDetalleMensaje;

  constructor(config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModalDetalleMensaje(cajaChicaModal) {
    this.modalDetalleMensaje = this.modalService.open(cajaChicaModal, { size: 'xl' });
  }

}
