import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-caja-chica',
  templateUrl: './caja-chica.component.html',
  styleUrls: ['./caja-chica.component.css']
})
export class CajaChicaComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  selectMes;

  idAnioActual;
  idMesActual;

  //Variable para las cargas
  cargandoTabla = false;

  //Variable que almacena los valores obtenidos para la tabla caja chica
  datosCajaChica;

  //variable para asociar al modal
  modalCajaChica;

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

  openModalCajaChica(cajaChicaModal) {
    this.modalCajaChica = this.modalService.open(cajaChicaModal, { size: 'xl' });
    this.cargarFechasActuales();
  }

  //Abrir visor de PDF
  openPDF(content) {
    this.modalService.open(content, {size: 'lg'});
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
        this.cargarTablaCajaChica();
      },
      error => {
        console.log(error);
      }
    )
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

  cargarTablaCajaChica(){
    this.cargandoTabla = true;
    this._portalSociosService.getCajaChica(this.idAnioActual, this.idMesActual).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoTabla = false;
      }else{
        this.datosCajaChica = response;
        this.cargandoTabla = false;
      }
    },
    error => {
      console.log(error);
      this.cargandoTabla = false;
    });
  }

  limpiarTabla(){
    this.datosCajaChica = '';
  }

  recargarTabla(){
    this.limpiarTabla();
    this.cargarTablaCajaChica();
  }

}
