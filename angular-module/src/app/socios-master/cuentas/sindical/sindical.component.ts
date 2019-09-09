import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-sindical',
  templateUrl: './sindical.component.html',
  styleUrls: ['./sindical.component.css']
})
export class SindicalComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  selectMes;

  idAnioActual;
  idMesActual;

  //Variable para las cargas
  cargandoTabla = false;
  cargarDatos = 0;

  //Variables que almacenan los valores obtenidos para la tabla
  montoInicio;
  tablaSindical;
  fijos;
  variable;
  cajaChica;
  prestamo;
  camping;
  resultado;

  //variable asociada al modalSindical
  modalTablaSindical;

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

  openModalTablaSindical(tablaSindical){
    this.modalTablaSindical = this.modalService.open(tablaSindical, {size: 'xl'});
    this.cargarFechasActuales();
  }

  //Abrir visor de PDF
  open(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  cargarFechasActuales(){
    this.cargarDatos = 0;
    //Cargar id del Año actual
    this._portalSociosService.getAnioActual().subscribe(
     response => {
       this.idAnioActual = response.id;
       this.cargarDatos++;
       console.log(this.cargarDatos);
       if(this.cargarDatos == 2){
        this.cargarTablaSindical();
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
       console.log(this.cargarDatos);
       if(this.cargarDatos == 2){
        this.cargarTablaSindical();
       }       
     },
     error => {
       console.log(error);
     }
   )
 }

 cargarTablaSindical(){
   this.cargandoTabla = true;
   this._portalSociosService.getTablaSindical(this.idAnioActual, this.idMesActual).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoTabla = false;
      }else{
        this.cargandoTabla = false;
        this.montoInicio = response.monto_inicio;
        this.cajaChica = response.CS.caja_chica;
        this.camping = response.CS.camping;
        this.fijos = response.CS.fijos;
        this.prestamo = response.CS.prestamos;
        this.variable = response.CS.variables;
        this.resultado = response.totales;
      }
   },
   error => {
     console.log(error);
     this.cargandoTabla = false;
   });
 }

 recargarTablaSindical(){
  this.limpiarTabla();
   this.cargarTablaSindical();
 }

 changeAnio(valorSelect){
   this.limpiarTabla();
   this.idAnioActual = valorSelect.target.value;
   this.recargarTablaSindical();
 }

 changeMes(valorSelect){
  this.limpiarTabla();
  this.idMesActual = valorSelect.target.value;
  this.recargarTablaSindical();
 }

 limpiarTabla(){
  this.montoInicio = null;
  this.cajaChica = null;
  this.camping = null;
  this.fijos = null;
  this.prestamo = null;
  this.variable = null;
  this.resultado = null;
 }

}
