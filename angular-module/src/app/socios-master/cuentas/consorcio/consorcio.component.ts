import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { BryanConsorcioService } from 'src/app/servicios/bryan-consorcio.service';

@Component({
  selector: 'app-consorcio',
  templateUrl: './consorcio.component.html',
  styleUrls: ['./consorcio.component.css']
})
export class ConsorcioComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  idAnioActual;

  //variable para almacenar los datos
  datosConsorcio;
  totalMensual;
  totalConsorcio;
  
  //variable para laws carags
  cargandoTabla = false;

  //variable para asociar al modal
  modalCajaChica;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService, private _consorcioService: BryanConsorcioService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
  }

  openModalConsorcio(consorcio){
    this.modalCajaChica = this.modalService.open(consorcio, { size: 'xl' });
    this.limpiarTabla();
    this.cargarFechasActuales();
  }

  cargarFechasActuales() {
    //Cargar id del Año actual
    this._portalSociosService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.cargarConsorcio();
      },
      error => {
        console.log(error);
      }
    );
  }

  changeAnio(valorSelect){
    this.limpiarTabla();
    this.idAnioActual = valorSelect.target.value;
    this.recargarTabla();
  }

  cargarConsorcio(){
    this.cargandoTabla = true;
    this._consorcioService.getConsorcio(this.idAnioActual).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoTabla = false;
      }else{
        console.log(response);
        this.datosConsorcio = response.mensual[0];
        this.totalMensual = response.DSCE[0];
        this.totalConsorcio= response.anual;
        this.cargandoTabla = false;
      }
    },
    error => {
      console.log(error);
      this.cargandoTabla = false;
    });
  }

  limpiarTabla(){
    this.datosConsorcio = '';
    this.totalConsorcio = '';
  }

  recargarTabla(){
    this.limpiarTabla();
    this.cargarConsorcio();
  }

}
