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
    this.getUltimaReunion();
  }

  getUltimaReunion(){
    this._reunionesService.getReunionInactiva().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert('Ha ocurrido un error al traer la ultima reunión realizada');
      }else{
        this.datosMensaje = response;
      }
    }, error => {
      console.log(error);
    });
  }

  abrirModalHistorial(modalHistorial){
    this.modalHistorialMensaje = this.modalService.open(modalHistorial, {size: 'xl'});
  }

}
