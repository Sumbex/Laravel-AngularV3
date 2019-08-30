import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-camping',
  templateUrl: './camping.component.html',
  styleUrls: ['./camping.component.css']
})
export class CampingComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  selectMes;

  idAnioActual;
  idMesActual;

  //Variable para las cargas
  cargandoTabla = false;

  //Variable que almacena los valores obtenidos para la tabla caja chica
  datosCamping;
  totalesCamping;
  montoAcumulado;

  //variable para asociar al modal
  modalCamping;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

  //Abrir visor de PDF
  openPDF(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  openModalCamping(Modal) {
    this.modalCamping = this.modalService.open(Modal, { size: 'xl' });
    this.cargarFechasActuales();
  }

  cargarFechasActuales() {
    //Cargar id del Año actual
    this._portalSociosService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
      },
      error => {
        console.log(error);
      }
    )
    //Cargar id del Mes actual
    this._portalSociosService.getMesActual().subscribe(
      response => {
        this.idMesActual = response.id;
        this.cargarCamping();
      },
      error => {
        console.log(error);
      }
    )
  }

  cargarCamping(){
    this.cargandoTabla = true;
    this._portalSociosService.getCamping(this.idAnioActual, this.idMesActual).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoTabla = false;
      }else{
        this.datosCamping = response;
        this.totalesCamping = response.totales;
        this.montoAcumulado = response.monto_inicio;
        this.cargandoTabla = false;
      }
    },
    error => {
      console.log(error);
      this.cargandoTabla = false;
    });
  }

  changeAnio(valorSelect){
    this.limpiarTabla();
    this.idAnioActual = valorSelect.target.value;
    this.recargarTabla();
  }
 
  changeMes(valorSelect){
   this.limpiarTabla();
   this.idMesActual = valorSelect.target.value;
   this.recargarTabla();
  }

  limpiarTabla(){
    this.datosCamping = '';
    this.totalesCamping = '';
    this.montoAcumulado = '';
  }

  recargarTabla(){
    this.limpiarTabla();
    this.cargarCamping();
  }

}
