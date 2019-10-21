import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { BryanConsorcioService } from 'src/app/servicios/bryan-consorcio.service';

@Component({
  selector: 'app-desvinculados',
  templateUrl: './desvinculados.component.html',
  styleUrls: ['./desvinculados.component.css']
})
export class DesvinculadosComponent implements OnInit {

  //variable para guardar los desvinculados
  datosDesvinculados;

  //Variables para los select de año y mes
  selectAnio;
  idAnioActual;

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

  openModal(consorcio){
    this.modalCajaChica = this.modalService.open(consorcio, { size: 'xl' });
    this.limpiarTabla();
    this.cargarFechasActuales();
  }

  cargarFechasActuales() {
    //Cargar id del Año actual
    this._portalSociosService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.recargarTabla();
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

  cargarDesvinculados(){
    this.cargandoTabla = true;
    this._consorcioService.getDesvinculados(this.idAnioActual).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoTabla = false;
      }else{
        this.datosDesvinculados = response.socios;
        console.log(this.datosDesvinculados);
        this.cargandoTabla = false;
      }
    },
    error => {
      console.log(error);
      this.cargandoTabla = false;
    });
  }

  limpiarTabla(){
    this.datosDesvinculados = '';
  }

  recargarTabla(){
    this.limpiarTabla();
    this.cargarDesvinculados();
  }

}
