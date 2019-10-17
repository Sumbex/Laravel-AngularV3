import { Component, OnInit } from '@angular/core';
import { ReunionesService } from 'src/app/servicios/reuniones.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-general-reunion',
  templateUrl: './general-reunion.component.html',
  styleUrls: ['./general-reunion.component.css']
})
export class GeneralReunionComponent implements OnInit {

  //variable para alojar los datos del mensaje
  datosReunion;
  datosReunionPasada;

  //variable para asociar al modal
  modalVariable;

  constructor(private _reunionesService: ReunionesService, config: NgbModalConfig, private modalService: NgbModal) { }

  ngOnInit() {
  }

  abrirModal(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'xl'});
  }

  getDetalleReunion(){
    this._reunionesService.getReunionActiva().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosReunion = response;
      }
    }, error => {
      console.log(error);
    });
  }

  getUltimaReunion(){
    this._reunionesService.getReunionInactiva().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosReunionPasada = response;
      }
    }, error => {
      console.log(error);
    });
  }

}
