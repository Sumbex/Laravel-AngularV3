import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ReunionesService } from 'src/app/servicios/reuniones.service';

@Component({
  selector: 'app-detalle-reunion',
  templateUrl: './detalle-reunion.component.html',
  styleUrls: ['./detalle-reunion.component.css']
})
export class DetalleReunionComponent implements OnInit {

  //Variables para almacenar el motivo
  motivoInasistencia = {
    mensaje:'',
    motivo:'1'
  }

  //Variable para las cargas del boton
  loadingEnvio = false;

  //variable para alojar los datos del mensaje
  datosReunion;

  //variable para asociar al modal
  modalDetalleMensaje;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _reunionesService: ReunionesService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
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

  enviarJustificacion(){
    console.log("los datos a enviar son los siguientes: " +  this.motivoInasistencia.mensaje + this.motivoInasistencia.motivo);
    this.loadingEnvio = true;
    this._reunionesService.setDatosJustificacion(this.motivoInasistencia).subscribe(response =>{
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.loadingEnvio = false;
        alert("Justificación ingresada correctamente");
      }
    }, error => {
      this.loadingEnvio = false;
      console.log(error);
    });
  }

  abrirModalDetalleMensaje(cajaChicaModal) {
    this.modalDetalleMensaje = this.modalService.open(cajaChicaModal, { size: 'xl' });
    this.getDetalleReunion();
  }

}
