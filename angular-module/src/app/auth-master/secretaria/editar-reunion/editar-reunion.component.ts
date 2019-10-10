import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SecretariaService } from 'src/app/servicios/secretaria.service';

@Component({
  selector: 'app-editar-reunion',
  templateUrl: './editar-reunion.component.html',
  styleUrls: ['./editar-reunion.component.css']
})
export class EditarReunionComponent implements OnInit {

  //variable para alojar los datos del mensaje
  datosReunion;

  //variable para asociar al modal
  modalVariable;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _secretariaService: SecretariaService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModal(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'xl'});
  }
  modalConfirmacion(confirmacion){
    this.modalVariable = this.modalService.open(confirmacion, {size: 'lg', centered: true});
  }

  getDatosReunion(){
    this._secretariaService.getReunionActiva().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert('Ha ocurrido un error al traer la reunióna activa');
      }else{
        this.datosReunion = response;
      }
    },error=>{
      console.log(error);
    });
  }

  editarReunion(formulario){
    if(!formulario){
      alert('Ha ocurrido un error en el formulario, revisar que todos los datos sean correctos');
    }else{
      this._secretariaService.updateNuevaReunion(this.datosReunion).subscribe(response => {
        if(response.estado == 'failed' || response.estado == 'failed_v'){
          alert('Ha ocurrido un error, revisar nuevamente el formualario');
        }else{
          alert(response.mensaje);
        }
      }, error=>{
        console.log(error);
      });
    }
  }

}
