import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SecretariaService } from 'src/app/servicios/secretaria.service';

@Component({
  selector: 'app-historial-reunion',
  templateUrl: './historial-reunion.component.html',
  styleUrls: ['./historial-reunion.component.css']
})
export class HistorialReunionComponent implements OnInit {

  //Variable para guardar reuniones
  listaReuniones;
  detalleReunion;
  asistenciaReunion;

  //Variable para guardar la lista de asistencia
  listaAsistenciaCompleta;
  cantidadInasistentes ;
  cantidadAsistentes;
  cantidadJustificados;

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
    this.getListaReuniones();
  }

  abrirDetalleReunion(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'xl', centered: true});
  }

  abrirAsistenciaReunion(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'xl', centered: true});
  }

  getListaReuniones(){
    this._secretariaService.getListaReuniones().subscribe(response =>{
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.listaReuniones = response;
      }
    },error=>{
      console.log(error);
    });
  }

  //TRAER LISTA DE ASISTENCIA COMPLETA
  getListaAsistentesCompleta(id){
    this._secretariaService.getListaAsistenciaCompleta(id).subscribe(response =>{
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.listaAsistenciaCompleta = response.lista;
        this.cantidadInasistentes = response.inasistentes;
        this.cantidadAsistentes = response.presentes;
        this.cantidadJustificados = response.justificados;
      }
    }, error => {
      console.log(error);
    });
  }

}
