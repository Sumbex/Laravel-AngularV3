import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AcuerdoAsambleaService } from 'src/app/servicios/acuerdo-asamblea.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { Acuerdo } from 'src/app/modelos/acuerdo.model';

@Component({
  selector: 'app-acuerdo-asamblea',
  templateUrl: './acuerdo-asamblea.component.html',
  styleUrls: ['./acuerdo-asamblea.component.css']
})
export class AcuerdoAsambleaComponent implements OnInit {

  //Variable del formulario
  datosActa = {
    tituloActa: '',
    fechaActa: '',
    tipoActa: '1',
    contenidoActa: '',
    estadoActa: '1'
  }

  acuerdo: Acuerdo = new Acuerdo();

  //Tabla de Acuerdos
  tablaAcuerdos;

  //Variables para los select de año y mes
  selectAnio;
  idAnioActual;

  //variable para asociar al modal
  modalVariable;
  modalVariabletest;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _acuerdoService: AcuerdoAsambleaService, private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
  }

  abrirModalAcuerdos(modalMenu){
    this.modalVariable = this.modalService.open(modalMenu, {size: 'xl'});
    this.getListaAcuerdosAsamblea();
    this.cargarFechasActuales();
  }

  abrirModalTest(modalMenutest, acuerdoId: string){
    this.acuerdo = new Acuerdo();
    this.modalVariabletest = this.modalService.open(modalMenutest, {size: 'xl'});
    this._acuerdoService.getAcuerdoPorId(acuerdoId).subscribe(response => {
      this.acuerdo = response.acuerdo;
      this.acuerdo.fecha = this.acuerdo.fecha.replace(/ /g, 'T');
      console.log(this.acuerdo.fecha);
    }, error => {
      console.log(error);
    });
  }

  cargarFechasActuales() {
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
      },
      error => {
        console.log(error);
      }
    )
  }

  changeAnio(valorSelect){
    this.idAnioActual = valorSelect.target.value;
  }

  limpiarFormulario(){
    this.datosActa.tituloActa = '';
    this.datosActa.fechaActa = '';
    this.datosActa.tipoActa = '1';
    this.datosActa.contenidoActa = '';
    this.datosActa.estadoActa = '1';

    console.log(this.datosActa);
  }

  setAcuerdoAsamblea(){
    this._acuerdoService.setAcuerdoAsamblea(this.datosActa).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.limpiarFormulario();
        alert(response.mensaje);
      }
    }, error=>{
      console.log(error);
    });
  }

  getListaAcuerdosAsamblea(){
    this._acuerdoService.getAcuerdosAsamblea().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.tablaAcuerdos = response.acuerdos;
        console.log(this.tablaAcuerdos);
      }
    }, error => {
      console.log(error);
    });
  }

  actualizarAcuerdo(){
    this._acuerdoService.updateAcuerdo(this.acuerdo).subscribe(response => {
      alert(response.mensaje);
      this.modalVariabletest = this.modalService.dismissAll();
      this.getListaAcuerdosAsamblea();
    }, error=> {
      console.log(error);      
    });
  }

}
