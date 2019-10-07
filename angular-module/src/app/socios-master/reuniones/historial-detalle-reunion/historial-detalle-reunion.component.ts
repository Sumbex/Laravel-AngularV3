import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ReunionesService } from 'src/app/servicios/reuniones.service';

@Component({
  selector: 'app-historial-detalle-reunion',
  templateUrl: './historial-detalle-reunion.component.html',
  styleUrls: ['./historial-detalle-reunion.component.css']
})
export class HistorialDetalleReunionComponent implements OnInit {

  //variable para alojar los datos del mensaje
  datosMensaje;

  //variable para asociar al modal
  modalHistorialMensaje;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _reunionesService: ReunionesService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModalHistorial(modalHistorial){
    this.modalHistorialMensaje = this.modalService.open(modalHistorial, {size: 'xl'});
  }

}
