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
  datosReunion = {
    id: '',
    creadaPor: '',
    fecha: '',
    tipoReunion: '1',
    titulo: '',
    descripcion: ''
  };

  datosActivaReunion;

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
    this.getDatosReunion();
  }
  modalConfirmacion(confirmacion){
    this.modalVariable = this.modalService.open(confirmacion, {size: 'lg', centered: true});
  }

  getDatosReunion(){
    this._secretariaService.getReunionActiva().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosActivaReunion = response.reunion[0];
        this.datosReunion.id = this.datosActivaReunion.id;
        this.datosReunion.creadaPor = this.datosActivaReunion.creada_por;
        this.datosReunion.fecha = this.datosActivaReunion.fecha_inicio;
        this.datosReunion.tipoReunion = this.datosActivaReunion.tipo;
        this.datosReunion.titulo = this.datosActivaReunion.titulo;
        this.datosReunion.descripcion = this.datosActivaReunion.descripcion;
        console.log(this.datosActivaReunion);
        console.log('br');
        console.log(this.datosReunion);
      }
    },error=>{
      console.log(error);
    });
  }

  editarReunionForm(formulario){
    if(!formulario){
      alert('Ha ocurrido un error en el formulario, revisar que todos los datos sean correctos');
    }else{
      this._secretariaService.updateNuevaReunion(this.datosReunion).subscribe(response => {
        if(response.estado == 'failed' || response.estado == 'failed_v'){
          alert(response.mensaje);
        }else{
          alert(response.mensaje);
        }
      }, error=>{
        console.log(error);
      });
    }
  }

  cancelarReunion(){
    this._secretariaService.cancelarReunion(this.datosReunion).subscribe(response =>{
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        alert(response.mensaje);
        document.getElementById('cerrarConfirmacion').click();
      }
    },error=>{
      console.log(error);
    });
  }

}
