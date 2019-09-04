import { Component, OnInit } from '@angular/core';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-prestamos-socios',
  templateUrl: './prestamos-socios.component.html',
  styleUrls: ['./prestamos-socios.component.css']
})
export class PrestamosSociosComponent implements OnInit {

  //variable para asociar al modal
  modalPagosPrestamos;

  //Objeto con los datos del Prestamo
  datosPrestamos;
  historialCuotas;
  historialAbonos;

  //Bloquear Muestro de pagos
  hideCuotas = true;
  hideAbonos = true;

  //Loading tabla
  loadingTabla = false;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.getPrestamos();
  }

  openModalHistorialPagos(historial) {
    this.modalPagosPrestamos = this.modalService.open(historial, { size: 'xl' });
  }

  getPrestamos(){
    this._portalSociosService.getPrestamosSocios().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosPrestamos = response.prestamos;
        console.log(this.datosPrestamos);
      }
    });
  }

  getPagosPrestamos(id, tipo){
    this._portalSociosService.getPagosPrestamos(id, tipo).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.hideCuotas = true;
      }else{
        this.hideCuotas = false;
        this.historialCuotas = response.mensaje;
      }
    });
  }

  getPagosAbonos(id, tipo){
    this._portalSociosService.getPagosAbonos(id, tipo).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.hideAbonos = true;
      }else{
        this.hideAbonos = false;
        this.historialAbonos = response.mensaje;
      }
    });
  }

}
