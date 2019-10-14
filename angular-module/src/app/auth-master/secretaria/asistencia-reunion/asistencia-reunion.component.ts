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

  //Lista de Asistentes
  listaDeAsistentes;

  //Lista de Asistencia Completa con contador
  listaAsistenciaCompleta;
  cantidadInasistentes  = 0;

  //Total de Ganancias por ianssitencvias
  totalGanancias = 0;

  //Variable de carga
  cargandoSocio = false;
  cargandoCambioEstadoSocio = false;

  cargandoReunion = false;

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

  abrirModalMenu(modalMenu){
    this.modalVariable = this.modalService.open(modalMenu, {size: 'xl'});
  }

  abrirModalAsistencia(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'xl'});
  }

  abrirConfirmacion(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'lg', centered: true});
  }

  //TRAER REUNION FINALIZADA O CERRADA
  getReunionCerradaFinalizada(){
    this.cargandoReunion = true;
    this._secretariaService.getReunionCerradaFinalizada().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoReunion = false;
      }else{
        this.datosReunion = response.reunion[0];
        this.getListaAsistentes();
        this.getListaAsistentesCompleta();
        this.cargandoReunion = false;
      }
    }, error=>{
      console.log(error);
      this.cargandoReunion = false;
    });
  }

  //TRAER LOS DATOS DEL USUARIO PARA RELLENAR
  getUsuario(){
    this.cargandoSocio = true;
    this.limpiarCampos();
    this._secretariaService.getUsuario(this.rutSocio, this.datosReunion.id).subscribe(response =>{
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
    this.cargandoCambioEstadoSocio = true;
    this._secretariaService.marcarAsistenciaUsuario(this.datosSocio.id, this.datosReunion.id).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoCambioEstadoSocio = false;
      }else{
        alert(response.mensaje);
        this.cargandoCambioEstadoSocio = false;
        this.getListaAsistentes();
        this.getListaAsistentesCompleta();
        this.getUsuario();
      }
    }, error=>{
      console.log(error);
      this.cargandoCambioEstadoSocio = false;
    });
  }

  //TRAER LISTA DE ASISTENTES A LA REUNIÓN
  getListaAsistentes(){
    this._secretariaService.getListaAsistentes(this.datosReunion.id).subscribe(response =>{
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.listaDeAsistentes = response.presentes;
        console.log(this.listaDeAsistentes);
      }
    }, error => {
      console.log(error);
    });
  }

  //TRAER LISTA DE ASISTENCIA COMPLETA
  getListaAsistentesCompleta(){
    this._secretariaService.getListaAsistenciaCompleta(this.datosReunion.id).subscribe(response =>{
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.listaAsistenciaCompleta = response.lista;
        this.cantidadInasistentes = response.inasistentes;
        console.log(this.cantidadInasistentes);
      }
    }, error => {
      console.log(error);
    });
  }

  //CALCULAR GANACIAS POR INASISTENCIAS
  calcularInasistencias(event){
    let valor = Math.ceil(event.target.value * this.cantidadInasistentes);
    this.totalGanancias = valor;
    console.log(this.totalGanancias);
  }

}
