import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SecretariaService } from 'src/app/servicios/secretaria.service';

@Component({
  selector: 'app-asistencia-reunion',
  templateUrl: './asistencia-reunion.component.html',
  styleUrls: ['./asistencia-reunion.component.css']
})
export class AsistenciaReunionComponent implements OnInit {
  //Variable de busquedas
  rutSocio;
  datosSocio = {
    id: '',
    nombre: '',
    estado: ''
  };

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

  abrirConfirmacion(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'lg', centered: true});
  }

  //TRAER LOS DATOS DEL USUARIO PARA RELLENAR
  getUsuario(){
    this._secretariaService.getUsuario(this.rutSocio).subscribe(response =>{
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosSocio = response;
      }
    },error=>{
      console.log(error);
    });
  }

  //MARCAR COMO ASISTENTE AL USUARIO ENCONTRADO
  marcarAsistenciaUsuario(){
    this._secretariaService.marcarAsistenciaUsuario(this.datosSocio.id).subscribe(response => {
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
