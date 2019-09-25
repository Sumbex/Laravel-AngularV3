import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-fondos-mutuos-socio',
  templateUrl: './fondos-mutuos-socio.component.html',
  styleUrls: ['./fondos-mutuos-socio.component.css']
})
export class FondosMutuosSocioComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  idAnioActual;

  //Variable para las cargas
  cargandoTabla = false;

  //variable para asociar al modal
  modalPagosPrestamos;

  //Objeto con los datos del Prestamo
  datosFondosMutuos;

  //Bloquear Muestro de pagos

  //Loading tabla
  loadingTabla = true;


  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.cargarFechasActuales();
  }

  changeAnio(valorSelect){
    this.limpiarTabla();
    this.idAnioActual = valorSelect.target.value;
    this.recargarTabla();
  }

  cargarFechasActuales() {
    //Cargar id del Año actual
    this._portalSociosService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.getFondosMutuos();
      },
      error => {
        console.log(error);
      }
    )
  }

  getFondosMutuos(){
    this.cargandoTabla = true;
    this._portalSociosService.getFondosMutuos().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoTabla = false;
      }else{
        this.datosFondosMutuos = response;
        this.cargandoTabla = false;
      }
    },
    error=> {
      console.log(error);
      this.cargandoTabla = false;
    })
  }

  limpiarTabla(){
    this.datosFondosMutuos = '';
  }

  recargarTabla(){
    this.limpiarTabla();
    this.getFondosMutuos();
  }

}
