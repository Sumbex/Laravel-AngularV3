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
  historialAbonoDiaSueldo;
  historialAbonoTrimestral;
  historialAbonoTerminoConflicto;

  //Bloquear Muestro de pagos
  hideCuotas = true;
  hideAbonosDiaSueldo = true;
  hideAbonosConflicto = true;
  hideAbonosTrimestral = true;
  hideAllAbonos = true;

  //Loading tabla
  loadingTabla = true;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.getPrestamos();
  }

  openPDF(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  openModalHistorialPagos(historial) {
    this.modalPagosPrestamos = this.modalService.open(historial, { size: 'xl' });
    this.limpiarHistorial();
    this.loadingTabla = true;
    this.hideCuotas = true;
    this.hideAbonosDiaSueldo = true;
    this.hideAbonosConflicto = true;
    this.hideAbonosTrimestral = true;
    this.hideAllAbonos = true;
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
        this.hideCuotas = true;
        this.historialCuotas = ['Apple', 'Orange', 'Banana'];
        console.log(this.historialCuotas);
      }else{
        this.hideCuotas = false; //mostrar los prestamos encontrados
        this.historialCuotas = response.mensaje;
        console.log(this.historialCuotas);
      }
    });
  }

  getPagosAbonos(id, tipo){
    this._portalSociosService.getPagosAbonos(id, tipo).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        if(tipo == 1){
          this.historialAbonoDiaSueldo = ['Apple', 'Orange', 'Banana']; 
          this.hideAbonosDiaSueldo = true;
          console.log(this.historialAbonoDiaSueldo);
        }else if(tipo == 2){
          this.historialAbonoTerminoConflicto = ['Apple', 'Orange', 'Banana']; 
          this.hideAbonosConflicto = true;
          console.log(this.historialAbonoTerminoConflicto);
        }else if(tipo == 3){
          this.historialAbonoTrimestral = ['Apple', 'Orange', 'Banana'];   
          this.hideAbonosTrimestral = true;
          this.hideAllAbonos = false;
          this.loadingTabla = false;
          console.log(this.historialAbonoTrimestral);
        }
      }else{
        if(tipo == 1){
          this.historialAbonoDiaSueldo = response.mensaje;
          this.hideAbonosDiaSueldo = false;
          console.log(this.historialAbonoDiaSueldo);
        }else if(tipo == 2){
          this.historialAbonoTerminoConflicto = response.mensaje;
          this.hideAbonosConflicto = false;
          console.log(this.historialAbonoTerminoConflicto);
        }else if(tipo == 3){
          this.historialAbonoTrimestral = response.mensaje;       
          this.hideAbonosTrimestral = false;
          /* this.hideCuotas = false; */
          this.hideAllAbonos = false;
          this.loadingTabla = false;
          console.log(this.historialAbonoTrimestral);
        }
      }
    });
  }

  limpiarHistorial(){
    this.historialAbonoDiaSueldo = '';
    this.historialAbonoTerminoConflicto = '';
    this.historialAbonoTrimestral = '';
    this.historialCuotas = '';
  }

}
