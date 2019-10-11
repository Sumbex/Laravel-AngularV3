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

  //Variable para rescatar la reunión
  datosReunion;

  //Variable de carga
  cargandoSocio = false;

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
    this.getReunionCerradaFinalizada();
  }

  abrirConfirmacion(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'lg', centered: true});
  }

  //TRAER REUNION FINALIZADA O CERRADA
  getReunionCerradaFinalizada(){
    this._secretariaService.getReunionCerradaFinalizada().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosReunion = response.reunion[0];
        console.log(this.datosReunion);
      }
    }, error=>{
      console.log(error);
    });
  }

  //TRAER LOS DATOS DEL USUARIO PARA RELLENAR
  getUsuario(){
    this.cargandoSocio = true;
    this.limpiarCampos();
    this._secretariaService.getUsuario(this.rutSocio).subscribe(response =>{
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoSocio = false;
      }else{
        this.datosSocio.id = response.socio.id;
        this.datosSocio.estado = response.socio.estado;
        this.datosSocio.nombre = response.socio.nombre;
        this.cargandoSocio = false;
      }
    },error=>{
      console.log(error);
      this.cargandoSocio = false;
    });
  }

  //LIMPIAR CAMPOS CON DATOS SOCIO
  limpiarCampos(){
    this.datosSocio.id = '';
    this.datosSocio.estado = '';
    this.datosSocio.nombre = '';
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
