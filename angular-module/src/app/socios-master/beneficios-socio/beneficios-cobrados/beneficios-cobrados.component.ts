import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-beneficios-cobrados',
  templateUrl: './beneficios-cobrados.component.html',
  styleUrls: ['./beneficios-cobrados.component.css']
})
export class BeneficiosCobradosComponent implements OnInit {

  //variable para asociar al modal
  modalPagosPrestamos;

  //Objeto con los datos del Prestamo
  datosNacidos;
  datosFallecidos;

  //Bloquear Muestro de pagos

  //Loading tabla
  loadingTabla = true;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.getNacimientosCobrados();
    this.getFallecimientosCobrados();
  }

  openPDF(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  getNacimientosCobrados(){
    this._portalSociosService.getBeneficiosNacimientosCobrados().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosNacidos = response;
      }
    },
    error => {
        console.log(error);
    });
  }

  getFallecimientosCobrados(){
    this._portalSociosService.getBeneficiosFalleciomientosCobrados().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosFallecidos = response;
      }
    },
    error => {
      console.log(error);
    });
  }

  limpiarTabla(){
    this.datosNacidos = '';
    this.datosFallecidos = '';
  }

}
