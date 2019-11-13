import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-gastos-operacionales',
  templateUrl: './gastos-operacionales.component.html',
  styleUrls: ['./gastos-operacionales.component.css']
})
export class GastosOperacionalesComponent implements OnInit {

  modalCajaChica;
  datosGastos;
  selectAnio;
  selectMes;

  idAnioActual;
  idMesActual;

  cargarDatos = 0;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _gastosService: SociosService,  private _portalSociosService: PortalSociosService) { }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
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

  cargarFechasActuales() {
    this.cargarDatos = 0;
    //Cargar id del Año actual
    this._portalSociosService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.cargarDatos++;
        if(this.cargarDatos == 2){
          this.traerGastosOperacionales();
        }
      },
      error => {
        console.log(error);
      }
    )
    //Cargar id del Mes actual
    this._portalSociosService.getMesActual().subscribe(
      response => {
        this.idMesActual = response.id;
        this.cargarDatos++;
        if(this.cargarDatos == 2){
          this.traerGastosOperacionales();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  
  limpiarTabla(){
    this.datosGastos = '';
  }

  recargarTabla(){
    this.limpiarTabla();
    this.traerGastosOperacionales();
  }

  openModalGastosOperacionales(gastosModal) {
    this.modalCajaChica = this.modalService.open(gastosModal, { size: 'xl' });
    this.limpiarTabla();
    this.cargarFechasActuales();
  }

  traerGastosOperacionales() {
    this._gastosService.traer_gastos(this.idAnioActual, this.idMesActual).subscribe(response => {
      if (response.estado == 'failed') {
        alert(response.mensaje);
      } else {
        this.datosGastos = response;
      }
    },error=>{
      console.log(error);
    });
  }

}
