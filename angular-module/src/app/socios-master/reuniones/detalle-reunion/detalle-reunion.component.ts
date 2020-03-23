import { Component, OnInit, Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ReunionesService } from 'src/app/servicios/reuniones.service';
import { stringify } from 'querystring';

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
   @Input() datoReunion;

  //variable para asociar al modal
  modalDetalleMensaje;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _reunionesService: ReunionesService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  enviarJustificacion(){
    this.loadingEnvio = true;
    this._reunionesService.setDatosJustificacion(this.datoReunion.id, this.motivoInasistencia.mensaje, this.motivoInasistencia.motivo).subscribe(response =>{
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        this.loadingEnvio = false;
        alert(response.mensaje);
      }else{
        this.loadingEnvio = false;
        alert(response.mensaje);
      }
    }, error => {
      this.loadingEnvio = false;
      console.log(error);
    });
  }

  getDatosJustificacion(){
    this._reunionesService.getDatosJustificacion(this.datoReunion.id).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        //alert(response.mensaje);
      }else{
        this.motivoInasistencia.mensaje = response.justificacion.descripcion;
        this.motivoInasistencia.motivo = response.justificacion.tipo;
      }
    },error=>{
      console.log(error);
    });
  }

  abrirModalDetalleMensaje(cajaChicaModal) {
    this.modalDetalleMensaje = this.modalService.open(cajaChicaModal, { size: 'xl' });
    this.getDatosJustificacion();
  }

}
